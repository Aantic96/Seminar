<?php

namespace App\Security;

use App\Entity\Page;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PageDeletionVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === 'DELETE' && $subject instanceof Page;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user) {
            return false;
        }

        if ($user->getRole()->getName() === 'ADMIN') {
            return true;
        }

        /** @var Page $page */
        $page = $subject;

        return $user === $page->getAuthor();
    }
}