<?php

namespace app\modules\media\dto;


class StorageDto extends \krok\storage\dto\StorageDto
{
    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->model->url;
    }
}