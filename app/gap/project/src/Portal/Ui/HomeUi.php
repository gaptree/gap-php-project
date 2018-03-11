<?php
namespace Gap\Project\Portal\Ui;

use Gap\Http\Response;

class HomeUi extends UiBase
{
    public function show(): Response
    {
        return new Response('show');
    }
}
