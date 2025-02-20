<?php

namespace App\DataPersister;

use ApiPlatform\State\ProcessorInterface;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Metadata\Operation;

readonly class TaskDataPersister implements ProcessorInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if (!$data instanceof Task) {
            return;
        }

        $data->setTitle(strtoupper($data->getTitle()));

        if ($data->getCreatedAt() === null) {
            $data->setCreatedAt(new \DateTimeImmutable());
        }

        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }
}
