<?php

require_once APPROOT . '/helpers/start.php';
require_once APPROOT . '/models/Note.php';

if (isLoggedIn()) {
    header('Content-type: text/xml');
    $noteModel = new Note();
    $notes = $noteModel->getAllNotes();
    $xml = new XMLWriter();
    $xml->openURI('php://output');
    $xml->startDocument();
    $xml->setIndent(true);
    $xml->startElement('list');
    foreach ($notes as $note) {
        $xml->startElement('note');
        $xml->writeElement('text', $note->text);
        $xml->writeElement('expiry', $note->expiry);
        $xml->writeElement('repeat', $note->repeat);
        $xml->writeElement('alarm', $note->alarm);
        $xml->endElement();
    }
    $xml->endElement();
    $xml->flush();
} else {
    header('location:' . URLROOT . '/pages/index');
}

    