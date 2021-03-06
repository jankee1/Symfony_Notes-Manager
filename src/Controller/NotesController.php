<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Notes;
use App\Form\NoteType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


#[Route('/notes', name: 'notes.')]
class NotesController extends AbstractController
{
    #[Route('/', name: 'show_notes', methods:['GET'])]
    public function show_notes(): Response
    {
        $notes = new Notes();
        $em = $this->getDoctrine()->getManager();
        $em->persist($notes);

        $retreivedNotes = $em->getRepository(Notes::class)->findAll();

        return $this->render('notes/show_notes.html.twig', [
            'controller_name' => 'NotesController',
            'note' => $retreivedNotes
        ]);
    }

    #[Route('/create', name: 'create_note', methods:['POST', 'GET'])]
    public function create_note(Request $request)
    {
      $newNote = new Notes();

      $form = $this->createForm(NoteType::class, $newNote);

      if($request->isMethod('POST')) {
          $form->handleRequest($request);
          if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($newNote);
            $em->flush();
            return $this->redirectToRoute('notes.show_notes');
          }
      }

      return $this->render('notes/create_note.html.twig', [
          'create_note_form' => $form->createView()
      ]);
    }

    #[Route('/edit/{noteId?}', name: "edit_note")]
    public function edit_note(Request $request, $noteId)
    {
      if(!isset($noteId) || !is_numeric($noteId))
        return $this->redirectToRoute('notes.show_notes');

      $em = $this->getDoctrine()->getManager();
      // $em->persist($notes);

      $selectedNote = $em->getRepository(Notes::class)->findOneBy([
        'id' => $noteId
      ]);

      $form = $this->createForm(NoteType::class, $selectedNote, [
        'action' => $this->generateUrl('notes.edit_note')
      ]);

      $form->handleRequest($request);

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

      return $this->redirectToRoute('notes.show_notes');
    }
}
