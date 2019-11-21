<?php
require 'includes/aws.phar';

use Aws\S3\S3Client;

use Aws\Exception\AwsException;

// The same options that can be provided to a specific client constructor can also be supplied to the Aws\Sdk class.
// Use the us-west-2 region and latest version of each client.
$sharedConfig = [
    'region' => 'ca-central-1',
    'version' => 'latest'
];

// Create an SDK class used to share configuration across clients.
$sdk = new Aws\Sdk($sharedConfig);

// Create an Amazon S3 client using the shared configuration data.
$s3Client = $sdk->createS3();

// Send a PutObject request and get the result object.
$result = $s3Client->putObject([
    'Bucket' => 'transitrating.tk-uploads',
    'Key' => 'test-upload-to-s3',
    'Body' => 'this is the body!'
]);

// Download the contents of the object.
$result = $s3Client->getObject([
    'Bucket' => 'transitrating.tk-uploads',
    'Key' => 'test-upload-to-s3'
]);

// Print the body of the result by indexing into the result object.
echo $result['Body'];

?>