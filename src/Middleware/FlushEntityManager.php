<?php declare(strict_types=1);

namespace Zain\LaravelDoctrine\Jetpack\Middleware;

use Closure;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Illuminate\Http\Request;

class FlushEntityManager
{
    private EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $this->em->flush();

        return $response;
    }
}
