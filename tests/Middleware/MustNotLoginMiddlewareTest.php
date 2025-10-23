<?php

namespace App\Middleware {

    require_once __DIR__ . '/../Helper/helper.php';

    use PHPUnit\Framework\TestCase;
    use App\Config\Database;
    use App\Domain\Session;
    use App\Domain\User;
    use App\Repository\SessionRepository;
    use App\Repository\UserRepository;
    use App\Service\SessionService;

    class MustNotLoginMiddlewareTest extends TestCase
    {

        private MustNotLoginMiddleware $middleware;
        private UserRepository $userRepository;
        private SessionRepository $sessionRepository;

        protected function setUp(): void
        {
            $this->middleware = new MustNotLoginMiddleware();
            putenv("mode=test");

            $this->userRepository = new UserRepository(Database::getConnection());
            $this->sessionRepository = new SessionRepository(Database::getConnection());

            $this->sessionRepository->deleteAll();
        }

        public function testMiddlewareCanBeCreated(): void
        {
            $this->assertInstanceOf(MustNotLoginMiddleware::class, $this->middleware);
        }
    }
}