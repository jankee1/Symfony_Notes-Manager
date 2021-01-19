<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Notes;


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
      return $this->render('notes/edit_note.html.twig', [
          'parameter' => $noteId,
      ]);
    }
    #[Route('/delete/{noteId?}', name: "delete_note")]
    public function delete_note($noteId)
    {
      return $this->render('notes/delete_note.html.twig', [
          'parameter' => $noteId,
      ]);
    }
}
