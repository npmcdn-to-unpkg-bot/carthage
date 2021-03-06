<?php

namespace AymardBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AymardBundle\Entity\Image;
use AymardBundle\Form\ImageType;

/**
 * Image controller.
 *
 * @Route("/admin/image")
 */
class ImageController extends Controller
{
    /**
     * Lists all Image entities.
     *
     * @Route("/", name="admin_image_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $images = $em->getRepository('AymardBundle:Image')->findAll();

        return $this->render('AymardBundle::admin/image/index.html.twig', array(
            'images' => $images,
        ));
    }
    
    
    /**
     * Creates a new Image entity.
     *
     * @Route("/new", name="admin_image_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $image = new Image();
        $form = $this->createForm('AymardBundle\Form\ImageType', $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $image->getFile();

            $fileName = $file->getClientOriginalName();

            $file->move(
                $this->container->getParameter('aymard.image_path'),
                $fileName
            );

            $image->setFile($fileName);


            $em = $this->getDoctrine()->getManager();
            $em->persist($image);
            $em->flush();

            return $this->redirectToRoute('admin_image_show', array('id' => $image->getId()));
        }

        return $this->render('AymardBundle::admin/image/new.html.twig', array(
            'image' => $image,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Image entity.
     *
     * @Route("/{id}", name="admin_image_show")
     * @Method("GET")
     */
    public function showAction(Image $image)
    {
        $deleteForm = $this->createDeleteForm($image);

        return $this->render('AymardBundle::admin/image/show.html.twig', array(
            'image' => $image,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Image entity.
     *
     * @Route("/{id}/edit", name="admin_image_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Image $image)
    {
        $deleteForm = $this->createDeleteForm($image);
        $editForm = $this->createForm('AymardBundle\Form\ImageEditType', $image);
        $editForm->handleRequest($request);
        $file = $image->getFile();

        if ($editForm->isSubmitted() && $editForm->isValid()) {
           
            $em = $this->getDoctrine()->getManager();
            $image->setFile($file);
            $em->persist($image);
            $em->flush();
            
            return $this->redirectToRoute('admin_image_edit', array('id' => $image->getId()));
        }
        
        return $this->render('AymardBundle::admin/image/edit.html.twig', array(
            'image' => $image,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Image entity.
     *
     * @Route("/{id}", name="admin_image_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Image $image)
    {
        $form = $this->createDeleteForm($image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fileName = $image->getFile();

            $this->removeFile($this->container->getParameter('aymard.image_path') . $fileName);
            
            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();
        }

        return $this->redirectToRoute('admin_image_index');
    }
    
    private function removeFile($file)
    {
        if (isset($file)) {
            unlink($file);
        }
    }

    /**
     * Creates a form to delete a Image entity.
     *
     * @param Image $image The Image entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Image $image)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_image_delete', array('id' => $image->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
