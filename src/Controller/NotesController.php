<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Notes;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


#[Route('/notes_list', name: 'notes.')]
class NotesController extends AbstractController
{
    #[Route('/', name: 'list', methods:['GET'])]
    public function notes_list(): Response
    {
        $notes = new Notes();
        $em = $this->getDoctrine()->getManager();
        $em->persist($notes);

        $retreivedNotes = $em->getRepository(Notes::class)->findAll();

        return $this->render('notes/notes_list.html.twig', [
            'controller_name' => 'NotesController',
            'note' => $retreivedNotes
        ]);
    }
    #[Route('/edit/{noteId?}', name: "edit_note")]
    public function edit_note($noteId)
    {
      $notes = new Notes();
      $em = $this->getDoctrine()->getManager();
      $em->persist($notes);

      $selectedNote = $em->getRepository(Notes::class)->findOneBy([
        'id' => $noteId
      ]);

      $form = $this->createFormBuilder($selectedNote)
        ->add('title', TextType::class, [
          'attr' => [
            'value' => $selectedNote->getTitle()
          ]
        ])
        ->add('description',TextareaType::class, [
          'attr' => [
            'value' => $selectedNote->getDescription()
          ]
        ])
        ->add('date',DateType::class, [
          'widget' => 'single_text',
          'attr' => [
            'datetime' => $selectedNote->getDate()->format('d/m/Y'),
          ]
        ])
        ->add('save', SubmitType::class, [
          'label' => 'Save'
        ])
        ->getForm()
      ;

      return $this->render('notes/edit_note.html.twig', [
          'parameter' => $noteId,
          'note' => $selectedNote,
          'note_edit_form' => $form->createView()
      ]);
    }

    #[Route('/delete/{noteId?}', name: "delete_note")]
    public function delete_note($noteId)
    {
      $em = $this->getDoctrine()->getManager();

      $noteToDelete = $em->getRepository(Notes::class)->find($noteId);
      $em->remove($noteToDelete);
      $em->flush();


      return $this->redirectToRoute('notes.list');
    }
}
