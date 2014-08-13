<?php
namespace Sergey\TestBundle\Extensions\VichUploader;

use Vich\UploaderBundle\Naming\NamerInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;

class MediaNamer implements NamerInterface
{
    /**
     * {@inheritDoc}
     */
    public function name($object, PropertyMapping $mapping)
    {
        $file = $mapping->getFile($object);
        return str_replace('.', '_', uniqid('', true)) . '.' . $file->guessClientExtension() ?: $file->guessExtension();
    }
}