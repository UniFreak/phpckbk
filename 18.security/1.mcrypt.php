<?php
/**
 * DEPRECATED & moved to PECL
 *
 * IV (init vector):
 *     used by some modes as part of the encrypt process.
 *     It's determined by the algo and the mode.
 *
 * mode
 * - CBC(Cipher Block Chaining):
 *     enc data in blocks, and use ecrypted value of each block to compute
 *     the encrypted value of the next block
 * - CFB(Cipher Feedback) / OFB(Output Ffeedback):
 *     also use IV, but enc data in units smaller than the block size
 *     OFB has security problem if you enc data in samller units than its
 *     block size
 * - ECB(Electronic Code Block):
 *     enc data in discrete blocks that don't depend on each other
 *     dones't use IV.
 *     less secure than other modes for repeated use, becuase same
 *     plain text with a given key always produces the same cipher
 *     text
 *
 * mode constants
 * - MCRYPT_MODE_ECB
 * - MCRYPT_MODE_CBC
 * - MCRYPT_MODE_CFB
 * - MCRYPT_MODE_OFB
 * - MCRYPT_MODE_NOFB
 * - MCRYPT_MODE_STREAM
 */
$algo = MCRYPT_BLOWFISH;
$key = 'That golden key that opens the palace of eternity.';
$data = 'The chicken escapes at down.';
$mode = MCRYPT_MODE_CBC;
$iv = mcrypt_create_iv(mcrypt_get_iv_size($algo, $mode), MCRYPT_DEV_URANDOM);

// enc
$encrypted = mcrypt_encrypt($algo, $key, $data, $mode, $iv);
$plain = base64_encode($encrypted);
echo $plain . "\n";

// dec
$encrypted = base64_decode($plain);
$decoded = mcrypt_decrypt($algo, $key, $encrypted, $mode, $iv);
// trim() will remove any tailing NULL bytes that mcrypt_decrypt() may
// have adde dto pad the output to be a whole number of 8-byte blocks
echo trim($decoded) . "\n";