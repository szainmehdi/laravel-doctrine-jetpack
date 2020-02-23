<?php declare(strict_types=1);

namespace Zain\LaravelDoctrine\Jetpack\Serializer;

use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

trait SerializesAttributes
{
    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        $normalizers = [$this->getObjectNormalizer(), $this->getDateTimeNormalizer()];
        $encoders = [new JsonEncoder()];

        $serialized = (new Serializer($normalizers, $encoders))->serialize($this, JsonEncoder::FORMAT);
        return json_decode($serialized, true);
    }

    /**
     * Get the instance in a JSON-able array.
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Get the instance as a JSON string
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        $normalizers = [$this->getObjectNormalizer(), $this->getDateTimeNormalizer()];
        $encoders = [new JsonEncoder(new JsonEncode([JsonEncode::OPTIONS => $options]))];
        return (new Serializer($normalizers, $encoders))->serialize($this, JsonEncoder::FORMAT);
    }

    protected function getObjectNormalizer(): ObjectNormalizer
    {
        return new ObjectNormalizer();
    }

    protected function getDateTimeNormalizer(): DateTimeNormalizer
    {
        return new DateTimeNormalizer();
    }
}

