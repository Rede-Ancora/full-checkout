<?php

declare (strict_types=1);
namespace Rector\DowngradePhp80\Rector\Instanceof_;

use PhpParser\Node;
use PhpParser\Node\Expr\Instanceof_;
use Rector\Core\Rector\AbstractRector;
use Rector\DowngradePhp81\NodeManipulator\ObjectToResourceReturn;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @changelog https://www.php.net/manual/en/migration80.incompatible.php#migration80.incompatible.resource2object
 *
 * @see \Rector\Tests\DowngradePhp80\Rector\Instanceof_\DowngradePhp80ResourceReturnToObjectRector\DowngradePhp80ResourceReturnToObjectRectorTest
 */
final class DowngradePhp80ResourceReturnToObjectRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var string[]
     */
    private const COLLECTION_OBJECT_TO_RESOURCE = [
        // curl
        'CurlHandle',
        'CurlMultiHandle',
        'CurlShareHandle',
        // socket
        'Socket',
        // GD
        'GdImage',
        // XMLWriter
        'XMLWriter',
        // XMLParser
        'XMLParser',
        // Broker
        'EnchantBroker',
        'EnchantDictionary',
        // OpenSSL
        'OpenSSLCertificate',
        'OpenSSLCertificateSigningRequest',
        // Shmop
        'Shmop',
        // MessageQueue
        'SysvMessageQueue',
        'SysvSemaphore',
        'SysvSharedMemory',
        // Inflate Deflate
        'InflateContext',
        'DeflateContext',
    ];
    /**
     * @var \Rector\DowngradePhp81\NodeManipulator\ObjectToResourceReturn
     */
    private $objectToResourceReturn;
    public function __construct(\Rector\DowngradePhp81\NodeManipulator\ObjectToResourceReturn $objectToResourceReturn)
    {
        $this->objectToResourceReturn = $objectToResourceReturn;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('change instanceof Object to is_resource', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function run($obj)
    {
        $obj instanceof \CurlHandle;
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    public function run($obj)
    {
        is_resource($obj) || $obj instanceof \CurlHandle;
    }
}
CODE_SAMPLE
)]);
    }
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\Instanceof_::class];
    }
    /**
     * @param Instanceof_ $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        return $this->objectToResourceReturn->refactor($node, self::COLLECTION_OBJECT_TO_RESOURCE);
    }
}
