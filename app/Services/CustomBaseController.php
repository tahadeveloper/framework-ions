<?php

namespace App\Services;

use App\Http\Controllers\Helpers\AdminAuth;
use App\Http\Controllers\Helpers\Partials;
use Ions\Bundles\Path;
use Ions\Bundles\Redirect;
use Ions\Foundation\BaseController;
use Ions\Support\Request;
use Twig\TwigFunction;

class CustomBaseController extends BaseController
{
    protected array $sharedData;
    protected int $userId;
    protected string $controlTitle = '';

    public function _loadInit(Request $request): void
    {
        parent::_loadInit($request);
        $this->twig
            ->addFunction(
                new TwigFunction('route', function (string $url = '', $attr = null) {
                    $url = str_replace('.', '/', $url);
                    if ($attr) {
                        if (is_array($attr)) {
                            $query = http_build_query($attr, '', '&', PHP_QUERY_RFC3986);
                            $query = str_replace('%3A', ':', $query);
                            $url .= '?' . $query;
                        } else {
                            $url .= '/' . $attr;
                        }
                    }
                    return Path::rootFolder($url);
                }),
            );
    }

    public function _loadedState(Request $request, $skipCheck = false): void
    {
        $this->sharedData = Partials::shared($this->twig);
        $this->userId = $this->sharedData['adminUser']->id;
        $this->twig->addGlobal('controlTitle', $this->controlTitle);

        $access = $request->attributes->get('_access', '');
        if (!$skipCheck && !AdminAuth::userData()->hasAccess(['admin'])
            && !AdminAuth::userData()->hasAccess([$access])) {
            // check if request is ajax
            if ($request->ajax()) {
                // make sure to header as unauthorized status
                header('HTTP/1.1 405 Unauthorized');
                display(toJson(['status' => 'error', 'message' => 'Unauthorized']));
            }
            Redirect::internal('misc/unauthorized');
        }
    }

}