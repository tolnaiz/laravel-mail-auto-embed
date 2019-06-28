<?php

namespace Eduardokum\LaravelMailAutoEmbed\Embedder;

use Swift_Image;
use Swift_Message;
use Swift_EmbeddedFile;
use Eduardokum\LaravelMailAutoEmbed\Models\EmbeddableEntity;

class AttachmentEmbedder extends Embedder
{
    /**
     * @var  Swift_Message
     */
    protected $message;

    /**
     * AttachmentEmbedder constructor.
     * @param  Swift_Message $message
     */
    public function __construct(Swift_Message $message)
    {
        $this->message = $message;
    }

    /**
     * @param  string  $url
     */
    public function fromUrl($url)
    {
        $filePath = str_replace(url('/'), public_path('/'), $url);

        if (!file_exists($filePath)) {
            return $url;
        }

        return $this->embed(
            Swift_Image::fromPath($filePath)
        );
    }

    /**
     * @param  EmbeddableEntity  $entity
     */
    public function fromEntity(EmbeddableEntity $entity)
    {
        return $this->embed(
            new Swift_EmbeddedFile(
                $entity->getRawContent(),
                $entity->getFileName(),
                $entity->getMimeType()
            )
        );
    }

    /**
     * @param  string  $base64
     */
    public function fromBase64($base64)
    {
        preg_match("#data:(image\/\w+);base64,#", $base64, $matches);
        
        $data = base64_decode(preg_replace("#data:image\/\w+;base64,#", "", $base64));

        return $this->embed(
            new Swift_Image(
                $data,
                sha1($data),
                $matches[1]
            )
        );
    }

    /**
     * @param  Swift_EmbeddedFile  $attachment
     * @return string
     */
    protected function embed(Swift_EmbeddedFile $attachment)
    {
        return $this->message->embed($attachment);
    }
}
