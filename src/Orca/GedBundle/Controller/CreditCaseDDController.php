<?php

namespace Orca\GedBundle\Controller;

use Orca\GedBundle\Entity\CreditCaseDD;
use Orca\GedBundle\Entity\metadatadd;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Creditcasedd controller.
 *
 * @Route("creditcasedd")
 */
class CreditCaseDDController extends Controller
{
    /**
     * Lists all creditCaseDD entities.
     *
     * @Route("/", name="creditcasedd_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $creditCaseDDs = $em->getRepository('OrcaGedBundle:CreditCaseDD')->findAll();

        return $this->render('OrcaGedBundle:creditcasedd:index.html.twig', array(
            'creditCaseDDs' => $creditCaseDDs,
        ));
    }

    /**
     * Creates a new creditCaseDD entity.
     *
     * @Route("/new", name="creditcasedd_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $creditCaseDD = new Creditcasedd();
        $form = $this->createForm('Orca\GedBundle\Form\CreditCaseDDType', $creditCaseDD);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            // $file stores the uploaded file
            $file = $creditCaseDD->getFile();
            $fileExtension = $file->guessExtension();



            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.'.$fileExtension;

            //create metadatadd of file
            $metadatadd = $this->createFileMetaData($file, $fileName);


            if(!is_null($metadatadd)){
                // Update the fileName property to store the file name
                $creditCaseDD->setFile($fileName);
                $em->persist($creditCaseDD);
                $em->flush($creditCaseDD);

                //update creditCase in metadatadd
                $metadatadd->setCreditcasedd($creditCaseDD);
                $em->persist($metadatadd);
                $em->flush($metadatadd);

                return $this->redirectToRoute('creditcasedd_show', array('id' => $creditCaseDD->getId()));

            }else{

                return $this->render('OrcaGedBundle:creditcasedd:new.html.twig', array(
                    'creditCaseDD' => $creditCaseDD,
                    'form' => $form->createView(),
                    'error' => true
                ));

            }



        }

        return $this->render('OrcaGedBundle:creditcasedd:new.html.twig', array(
            'creditCase' => $creditCaseDD,
            'form' => $form->createView(),
            'error' => false
        ));
    }

    /**
     * Finds and displays a creditCaseDD entity.
     *
     * @Route("/{id}", name="creditcasedd_show")
     * @Method("GET")
     */
    public function showAction(CreditCaseDD $creditCaseDD)
    {
        $deleteForm = $this->createDeleteForm($creditCaseDD);

        //get metadatas of credit case
        $em = $this->getDoctrine()->getManager();
        $metadatadd = $em->getRepository('OrcaGedBundle:metadatadd')->findBy(array('creditcasedd'=>$creditCaseDD->getId()));


        return $this->render('OrcaGedBundle:creditcasedd:show.html.twig', array(
            'creditCaseDD' => $creditCaseDD,
            'delete_form' => $deleteForm->createView(),
            'metadata'=>$metadatadd
        ));
    }

    /**
     * Displays a form to edit an existing creditCaseDD entity.
     *
     * @Route("/{id}/edit", name="creditcasedd_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CreditCaseDD $creditCaseDD)
    {
        $deleteForm = $this->createDeleteForm($creditCaseDD);
        $editForm = $this->createForm('Orca\GedBundle\Form\CreditCaseDDType', $creditCaseDD);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('creditcasedd_edit', array('id' => $creditCaseDD->getId()));
        }

        return $this->render('OrcaGedBundle:creditcasedd:edit.html.twig', array(
            'creditCaseDD' => $creditCaseDD,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a creditCaseDD entity.
     *
     * @Route("/{id}", name="creditcasedd_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CreditCaseDD $creditCaseDD)
    {
        $form = $this->createDeleteForm($creditCaseDD);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($creditCaseDD);
            $em->flush($creditCaseDD);
        }

        return $this->redirectToRoute('creditcasedd_index');
    }

    /**
     * Creates a form to delete a creditCaseDD entity.
     *
     * @param CreditCaseDD $creditCaseDD The creditCaseDD entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CreditCaseDD $creditCaseDD)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('creditcasedd_delete', array('id' => $creditCaseDD->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Creates the file metadatadd return false if file format invalid
     *
     * @param $file
     * @param $fileName
     * @return metadatadd
     */
    private function createFileMetaData($file, $fileName){

        $em = $this->getDoctrine()->getManager();
        $image_extensions = array( "png", "gif" ,"jpeg","jpg" );
        $doc_extensions = array( "pdf" );

        $fileExtension = $file->guessExtension();

        if(in_array($fileExtension, $image_extensions)){

            $url = 'https://api.idolondemand.com/1/api/sync/ocrdocument/v1';

            // Move the file to the directory where files are stored
            $file->move(
                $this->getParameter('files_directory'),
                $fileName
            );

            $filePath = $this->getParameter('files_directory').'/'.$fileName;
            $post = array('apikey' => '06d2f45e-f2b1-4655-a155-fb8920dd9e10',
                'mode' => 'document_photo',
                'file' => '@'.$filePath);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_POST,1);
            curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            $result=curl_exec ($ch);
            curl_close ($ch);

            $json = json_decode($result,true);



            if($json && isset($json['text_block']))
            {
                $textblock =$json['text_block'][0]['text'];
                $metadata = new metadatadd();
                $metadata->setContent($textblock);
                $metadata->setFileSize($file->getClientSize());
                $metadata->setContentType($fileExtension);
                $metadata->setCreatedDate(new \DateTime());
                $metadata->setPageCount(1);
                $em->persist($metadata);
                $em->flush($metadata);
            }

            return $metadata;

        }elseif(in_array($fileExtension, $doc_extensions)){

            $url = 'https://api.havenondemand.com/1/api/sync/extracttext/v1';

            // Move the file to the directory where files are stored
            $file->move(
                $this->getParameter('files_directory'),
                $fileName
            );

            $filePath = $this->getParameter('files_directory').'/'.$fileName;
            $post = array('apikey' => '06d2f45e-f2b1-4655-a155-fb8920dd9e10',
                'mode' => 'document_photo',
                'file' => '@'.$filePath);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_POST,1);
            curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            $result=curl_exec ($ch);
            curl_close ($ch);

            $json = json_decode($result,true);

            if($json && isset($json['document']))
            {
                $metadata = new metadatadd();
                $metadata->setContentType($fileExtension);

                $textblock =$json['document'][0]['content'];
                $metadata->setContent($textblock);

                $pageCount =$json['document'][0]['page_count'][0];
                $metadata->setPageCount((int)$pageCount);

                if(isset($json['document'][0]['author'][0])){
                    $author =$json['document'][0]['author'][0];
                    $metadata->setAuthor($author);
                }
                if(isset($json['document'][0]['created_date'][0])){
                    $createdDate =$json['document'][0]['created_date'][0];
                    $metadata->setCreatedDate(new \DateTime(date("Ymd", $createdDate)));
                }
                if(isset($json['document'][0]['modified_date'][0])){
                    $modifiedDate =$json['document'][0]['modified_date'][0];
                    $metadata->setModifiedDate(new \DateTime(date("Ymd", $modifiedDate)));
                }

                $fileSize =$json['document'][0]['file_size'][0];
                $metadata->setFileSize($fileSize);


                $em->persist($metadata);
                $em->flush($metadata);
            }

            return $metadata;

        }else{

            return null;

        }
    }


}
