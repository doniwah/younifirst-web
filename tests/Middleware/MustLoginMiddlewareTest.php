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

    class MustLoginMiddlewareTest extends TestCase
    {

        private MustLoginMiddleware $middleware;
        private UserRepository $userRepository;
        private SessionRepository $sessionRepository;

        protected function setUp(): void
        {
            $this->middleware = new MustLoginMiddleware();
            putenv("mode=test");

            $this->userRepository = new UserRepository(Database::getConnection());
            $this->sessionRepository = new SessionRepository(Database::getConnection());

            $this->sessionRepository->deleteAll();
        }
        public function testMiddlewareCanBeCreated(): void
        {
            $this->assertInstanceOf(MustLoginMiddleware::class, $this->middleware);
        }
    }
}