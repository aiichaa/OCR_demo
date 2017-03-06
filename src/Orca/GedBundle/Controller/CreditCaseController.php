<?php

namespace Orca\GedBundle\Controller;

use Orca\GedBundle\Entity\CreditCase;
use Orca\GedBundle\Entity\metadata;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Creditcase controller.
 *
 * @Route("creditcase")
 */
class CreditCaseController extends Controller
{
    /**
     * Lists all creditCase entities.
     *
     * @Route("/", name="creditcase_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $creditCases = $em->getRepository('OrcaGedBundle:CreditCase')->findAll();

        return $this->render('OrcaGedBundle:creditcase:index.html.twig', array(
            'creditCases' => $creditCases,
        ));
    }

    /**
     * Creates a new creditCase entity.
     *
     * @Route("/new", name="creditcase_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $creditCase = new Creditcase();
        $form = $this->createForm('Orca\GedBundle\Form\CreditCaseType', $creditCase);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            // $file stores the uploaded file
            $file = $creditCase->getFile();
            $fileExtension = $file->guessExtension();



            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.'.$fileExtension;

            //create metadata of file
            $metadata = $this->createFileMetaData($file, $fileName);


            if(!is_null($metadata)){
                // Update the fileName property to store the file name
                $creditCase->setFile($fileName);
                $em->persist($creditCase);
                $em->flush($creditCase);

                //update creditCase in metadata
                $metadata->setCreditcase($creditCase);
                $em->persist($metadata);
                $em->flush($metadata);

                return $this->redirectToRoute('creditcase_show', array('id' => $creditCase->getId()));

            }else{

                return $this->render('OrcaGedBundle:creditcase:new.html.twig', array(
                    'creditCase' => $creditCase,
                    'form' => $form->createView(),
                    'error' => true
                ));

            }



        }

        return $this->render('OrcaGedBundle:creditcase:new.html.twig', array(
            'creditCase' => $creditCase,
            'form' => $form->createView(),
            'error' => false
        ));
    }

    /**
     * Finds and displays a creditCase entity.
     *
     * @Route("/{id}", name="creditcase_show")
     * @Method("GET")
     */
    public function showAction(CreditCase $creditCase)
    {
        $deleteForm = $this->createDeleteForm($creditCase);

        //get metadatas of credit case
        $em = $this->getDoctrine()->getManager();
        $metadata = $em->getRepository('OrcaGedBundle:metadata')->findBy(array('creditcase'=>$creditCase->getId()));


        return $this->render('OrcaGedBundle:creditcase:show.html.twig', array(
            'creditCase' => $creditCase,
            'delete_form' => $deleteForm->createView(),
            'metadata'=>$metadata
        ));
    }

    /**
     * Displays a form to edit an existing creditCase entity.
     *
     * @Route("/{id}/edit", name="creditcase_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CreditCase $creditCase)
    {
        $deleteForm = $this->createDeleteForm($creditCase);
        $editForm = $this->createForm('Orca\GedBundle\Form\CreditCaseType', $creditCase);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('creditcase_edit', array('id' => $creditCase->getId()));
        }

        return $this->render('OrcaGedBundle:creditcase:edit.html.twig', array(
            'creditCase' => $creditCase,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a creditCase entity.
     *
     * @Route("/{id}", name="creditcase_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CreditCase $creditCase)
    {
        $form = $this->createDeleteForm($creditCase);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();


            //remove file from disk
            $file_path = $this->getParameter('files_directory')."/".$creditCase->getFile();
            var_dump($file_path);
            if(file_exists($file_path)) unlink($file_path);

            $em->remove($creditCase);
            $em->flush($creditCase);
        }

        return $this->redirectToRoute('creditcase_index');
    }

    /**
     * Creates a form to delete a creditCase entity.
     *
     * @param CreditCase $creditCase The creditCase entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CreditCase $creditCase)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('creditcase_delete', array('id' => $creditCase->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Creates the file metadata return false if file format invalid
     *
     * @param $file
     * @param $fileName
     * @return metadata
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
                $metadata = new metadata();
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
                $metadata = new metadata();
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
