<?php

namespace Orca\TesseractBundle\Controller;

use Orca\TesseractBundle\Entity\DataEntity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


use Ddeboer\Tesseract\Tesseract;

/**
 * Dataentity controller.
 *
 * @Route("dataentity")
 */
class DataEntityController extends Controller
{
    /**
     * Lists all dataEntity entities.
     *
     * @Route("/", name="dataentity_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $dataEntities = $em->getRepository('OrcaTesseractBundle:DataEntity')->findAll();

        return $this->render('OrcaTesseractBundle:dataentity:index.html.twig', array(
            'dataEntities' => $dataEntities,
        ));
    }

    /**
     * Creates a new dataEntity entity.
     *
     * @Route("/new", name="dataentity_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $dataEntity = new Dataentity();
        $form = $this->createForm('Orca\TesseractBundle\Form\DataEntityType', $dataEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();


            $file = $dataEntity->getFile();

            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            $file->move(
                $this->getParameter('files_directory'),
                $fileName
            );

            $dataEntity->setFile($fileName);

           /*$filePath = $this->getParameter('files_directory') .'/'. $fileName ;
           $tempPath = $this->getParameter('files_directory') .'/temp' ;*/

            // Retrieve the webpath in symfony
            $webPath = $this->get('kernel')->getRootDir().'/../web/';
            // The filename of the image is text.jpeg and is located inside the web folder
            $filepath = $webPath.'text.png';

            // Is useful to verify if the file exists, because the tesseract wrapper
            // will throw an error but without description
            /*if(!file_exists($filepath)){

                var_dump('file doesnt exist');

            }else{

                var_dump('file exists');

            }*/

            // Create a  new instance of tesseract and provide as first parameter
            // the local path of the image
         //   $tesseractInstance = new TesseractOCR($filepath);

            // Execute tesseract to recognize text
          //  $result = $tesseractInstance->run();


            $tesseract = new Tesseract();
            //$version = $tesseract->getVersion();
            //$languages = $tesseract->getSupportedLanguages();
            $text = $tesseract->recognize($filepath);


            var_dump($text);
            die();


            $em->persist($dataEntity);
            $em->flush($dataEntity);

            return $this->redirectToRoute('dataentity_show', array('id' => $dataEntity->getId(),
                                                                    'text' => $text));
        }

        return $this->render('OrcaTesseractBundle:dataentity:new.html.twig', array(
            'dataEntity' => $dataEntity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a dataEntity entity.
     *
     * @Route("/{id}", name="dataentity_show")
     * @Method("GET")
     */
    public function showAction(DataEntity $dataEntity)
    {
        $deleteForm = $this->createDeleteForm($dataEntity);

        return $this->render('OrcaTesseractBundle:dataentity:show.html.twig', array(
            'dataEntity' => $dataEntity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing dataEntity entity.
     *
     * @Route("/{id}/edit", name="dataentity_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, DataEntity $dataEntity)
    {
        $deleteForm = $this->createDeleteForm($dataEntity);
        $editForm = $this->createForm('Orca\TesseractBundle\Form\DataEntityType', $dataEntity);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('dataentity_edit', array('id' => $dataEntity->getId()));
        }

        return $this->render('OrcaTesseractBundle:dataentity:edit.html.twig', array(
            'dataEntity' => $dataEntity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a dataEntity entity.
     *
     * @Route("/{id}", name="dataentity_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, DataEntity $dataEntity)
    {
        $form = $this->createDeleteForm($dataEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($dataEntity);
            $em->flush($dataEntity);
        }

        return $this->redirectToRoute('dataentity_index');
    }

    /**
     * Creates a form to delete a dataEntity entity.
     *
     * @param DataEntity $dataEntity The dataEntity entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(DataEntity $dataEntity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('dataentity_delete', array('id' => $dataEntity->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

}
