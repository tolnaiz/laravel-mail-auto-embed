<?php

namespace Eduardokum\LaravelMailAutoEmbed\Embedder;

use Eduardokum\LaravelMailAutoEmbed\Models\EmbeddableEntity;

class Base64Embedder extends Embedder
{
    /**
     * @param  string  $url
     * @return string
     */
    public function fromUrl($url)
    {
        $filePath = str_replace(url('/'), public_path('/'), $url);

        if (!file_exists($filePath)) {
            return $url;
        }

        return $this->base64String(mime_content_type($filePath), file_get_contents($filePath));
    }

    /**
     * @param  string  $base64
     */
    public function fromBase64($base64)
    {
        return $base64;
    }

    /**
     * @param  EmbeddableEntity  $entity
     * @return string
     */
    public function fromEntity(EmbeddableEntity $entity)
    {
        return $this->base64String($entity->getMimeType(), $entity->getRawContent());
    }

    /**
     * @param  string  $mimeType
     * @param  mixed  $content
     */
    private function base64String($mimeType, $content)
    {
        return 'data:'.$mimeType.';base64,'.base64_encode($content);
    }
}
