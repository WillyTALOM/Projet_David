<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\Mime\Address;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, SluggerInterface $slugger, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getdata();
            $email = (new TemplatedEmail())
                ->from(new Address($contact['email'], $contact['first_name'] . '' . $contact['last_name']))
                ->to(new Address($this->getParameter('contact_email')))
                ->replyTo(new Address($contact['email'], $contact['first_name'] . ' ' . $contact['last_name']))
                ->subject('VDV - demande de contact - ' . $contact['subject'])
                ->htmltemplate('contact/email_contact.html.twig')
                ->context([
                    'firstName' => $contact['first_name'],
                    'lastName' => $contact['last_name'],
                    'company' => $contact['company'],
                    'emailAddress' => $contact['email'],
                    'subject' => $contact['subject'],
                    'message' => $contact['message'],

                ]);

            if ($contact['attachment'] !== null) {
                $originalFileName = pathinfo($contact['attachment']->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName . '.' . $contact['attachment']->guessExtension();
                $email->attachFromPath($contact['attachment']->getPathName(), $newFileName);
            }

            $mailer->send($email);
            $this->addFlash('success', 'Votre message a bien été envoyé. Merci de nous avoir contacté. Notre équipe va vous répondre dans les meilleurs délais.');
            return $this->redirectToRoute('contact');
        }
        return $this->render('contact/index.html.twig', [
            'contactForm' => $form->createView()
        ]);
    }
}
