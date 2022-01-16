<?php
declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User;
use phpDocumentor\Reflection\Types\Object_;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class GetAllController
 * @package App\Controller\User
 */
class GetOneController extends AbstractUserController
{
    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function __invoke(Request $request, Response $response): Response {
        /** @var User $user */
        $user = $request->getAttribute('user');

        return $this->jsonResponse($response, 'success', $user->toJson(), 200);
    }
}
