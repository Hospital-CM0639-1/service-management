<?php

namespace App\Common\Service\Utils\Encrypt;

use App\Common\Error\GenericException;

abstract class AbstractEncryptor
{
    /**
     * The encryption key decoded in base64 format
     *
     * @return string
     */
    abstract protected function getEncryptionKey(): string;

    /**
     * The suffix of encryptor
     *
     * @return string
     */
    protected function getEncryptedStringSuffix(): string
    {
        return '<ENC>';
    }

    /**
     * The encryption algorithm
     *
     * @return string
     */
    protected function getEncryptionAlgorithm(): string
    {
        return 'aes-256-cbc';
    }

    /**
     * Encrypt the given string
     *
     * @param ?string $stringToEncrypt
     * @throws GenericException if something went wrong
     */
    public function encrypt(?string $stringToEncrypt): ?string
    {
        # if NULL, return NULL
        if (is_null($stringToEncrypt)) {
            return null;
        }

        # if the string ends with the suffix, I suppose it's already encrypted
        $suffix = $this->getEncryptedStringSuffix();
        if (str_ends_with($stringToEncrypt, $suffix)) {
            return $stringToEncrypt;
        }

        $encryptionKey = $this->getDecodedEncryptionKey();
        $encryptionAlgorithm = $this->getEncryptionAlgorithm();

        # generate a random IV with correct length
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(cipher_algo: $encryptionAlgorithm));

        # string encryption
        $ciphertext = openssl_encrypt(
            data: $stringToEncrypt,
            cipher_algo: $encryptionAlgorithm,
            passphrase: $encryptionKey,
            options: OPENSSL_RAW_DATA,
            iv: $iv
        );

        # encode in base64 with the IV; at the end of the encoded string, I put the suffix
        return base64_encode($iv . $ciphertext) . $suffix;
    }

    /**
     * Decrypt the given string
     *
     * @param ?string $stringToDecrypt
     * @throws GenericException if something went wrong
     */
    public function decrypt(?string $stringToDecrypt): ?string
    {
        # if NULL, return NULL
        if (is_null($stringToDecrypt)) {
            return null;
        }

        # if the suffix is not present, I suppose it's not an encrypted string
        $suffix = $this->getEncryptedStringSuffix();
        if (!str_ends_with($stringToDecrypt, $suffix)) {
            return $stringToDecrypt;
        }

        # remove the suffix
        $stringToDecrypt = substr($stringToDecrypt, 0, -strlen($suffix));

        # if the given string is empty, I return it
        if (empty($stringToDecrypt)) {
            return $stringToDecrypt;
        }

        $encryptionKey = $this->getDecodedEncryptionKey();
        $encryptionAlgorithm = $this->getEncryptionAlgorithm();

        $stringToDecrypt = base64_decode($stringToDecrypt);

        $ivsize = openssl_cipher_iv_length($encryptionAlgorithm);
        $iv = mb_substr($stringToDecrypt, 0, $ivsize, '8bit');
        $ciphertext = mb_substr($stringToDecrypt, $ivsize, null, '8bit');

        return openssl_decrypt(
            data: $ciphertext,
            cipher_algo: $encryptionAlgorithm,
            passphrase: $encryptionKey,
            options: OPENSSL_RAW_DATA,
            iv: $iv
        );
    }

    /**
     * Get the key decoding base64 format and execute an evaluation of its length against the algorithm
     *
     * @return string the decoded key
     * @throws GenericException
     */
    private function getDecodedEncryptionKey(): string
    {
        $key = $this->getEncryptionKey();
        if (empty($key)) {
            throw new GenericException('Empty encryption key was provided');
        }

        # Decode the key
        $key = base64_decode($key);

        # if the length of the key is different from the required length for the algorithm
        if (mb_strlen($key, '8bit') !== openssl_cipher_key_length($this->getEncryptionAlgorithm())) {
            throw new GenericException('Empty encryption key was provided');
        }

        return $key;
    }
}