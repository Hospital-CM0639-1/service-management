<?php

namespace App\Api\Command;

use App\Api\Entity\ApiToken;
use App\Api\Service\ApiTokenEncryptor;
use App\Common\Entity\User;
use App\Common\Entity\UserType\UserType;
use App\Common\Enum\User\UserType\UserTypeCodeEnum;
use App\Common\Service\Utils\Generator\RandomStringGenerator;
use App\Common\Service\Utils\Helper\DoctrineHelper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'api:generate-api-token',
    description: 'Generate an API Token'
)]
class GenerateApiTokenCredentialCommand extends Command
{
    public function __construct(
        private readonly DoctrineHelper $doctrineHelper,
        private readonly ApiTokenEncryptor $apiTokenEncryptor,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption(
                name: 'name',
                mode: InputOption::VALUE_REQUIRED,
            )
            ->addOption(
                name: 'valid-from',
                mode: InputOption::VALUE_REQUIRED,
            )
            ->addOption(
                name: 'valid-to',
                mode: InputOption::VALUE_REQUIRED,
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $name = $input->getOption('name') ?: RandomStringGenerator::generate();
        $validFrom = $input->getOption('valid-from');
        $validTo = $input->getOption('valid-to');

        if (!empty($validFrom)) {
            $validFrom = \DateTime::createFromFormat('Y-m-d', $validFrom);
            if ($validFrom === false) {
                $io->error('Invalid valid-from was provided');
                return self::FAILURE;
            }
        }

        if (!empty($validTo)) {
            $validTo = \DateTime::createFromFormat('Y-m-d', $validTo);
            if ($validTo === false) {
                $io->error('Invalid valid-from was provided');
                return self::FAILURE;
            }
        }

        if ($validTo instanceof \DateTime && $validFrom instanceof \DateTime && $validTo->format('Y-m-d') < $validFrom->format('Y-m-d')) {
            $io->error('Valid to must be greater or equal to valid from');
            return self::FAILURE;
        }

        $apiUserType = $this->doctrineHelper->getRepository(UserType::class)->findOneBy([
            'code' => UserTypeCodeEnum::API
        ]);
        $user = (new User())
            ->setName($name)
            ->setSurname('API')
            ->setEmail(RandomStringGenerator::generate(length: 32))
            ->setUsername(RandomStringGenerator::generate(length: 32))
            ->setType($apiUserType)
            ->setPassword('-');

        $apiToken = (new ApiToken())
            ->setUser($user)
            ->setToken($this->apiTokenEncryptor->encrypt(stringToEncrypt: RandomStringGenerator::generate()))
            ->setName($name)
            ->setValidFrom($validFrom)
            ->setValidTo($validTo);
        $this->doctrineHelper->save([$user, $apiToken]);

        $table = new Table($output);
        $table
            ->setHeaders(['Token'])
            ->setRows([
                [
                    str_pad($user->getId(), "10", "0", STR_PAD_LEFT) . "_" . time() . "_" . $this->apiTokenEncryptor->decrypt(stringToDecrypt: $apiToken->getToken()),
                ],
            ])
        ;
        $table->render();

        return self::SUCCESS;
    }
}