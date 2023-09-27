<?php

namespace App\Security\Voter;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
class TasksVoter extends Voter
{
    const EDIT = 'EDIT';
    const DELETE = 'DELETE';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::EDIT, self::DELETE])) {
            return false;
        }

        if (!$subject instanceof Task) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /**
         * @var Task $task
         */
        $task = $subject;

        return match($attribute) {
            self::EDIT => $this->canEdit($task, $user),
            self::DELETE => $this->canDelete($task, $user),
            default => throw new \LogicException('Ce code ne doit pas être atteint')
        };
    }

    private function canEdit(Task $task, User $user): bool
    {
        if ($task->getUser() === $user) {
            return true;
        }

        return false;
    }

    private function canDelete(Task $task, User $user): bool
    {
        if ($task->getUser() === $user) {
            return true;
        }

        if ($task->getUser()->getUsername() === 'anonyme' && $this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return false;
    }
}
