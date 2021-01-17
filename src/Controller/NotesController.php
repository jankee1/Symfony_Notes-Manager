<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Notes;

class NotesController extends AbstractController
{
    #[Route('/notes_list/{noteId?}', name: 'notes_list', methods:['GET'])]
    public function notes_list($noteId): Response
    {
        $note = [
          'title' => 'this is title',
          'description' => 'this is description',
          'date' => 'this is date',
        ];
        return $this->render('notes/index.html.twig', [
            'controller_name' => 'NotesController',
            'noteId' => $noteId,
            'note' => $note
        ]);
    }
}
