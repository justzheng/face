<?php
/**
 * User: cyr
 * Date: 17/5/9
 */

namespace cyr\face;

class OrderQueryService extends PaybaseService
{
    public $request_url = FACE_SEARCH;

    public function query()
    {
        return $this->request();
    }

}
