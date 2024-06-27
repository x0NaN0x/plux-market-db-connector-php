<?php
declare(strict_types=1);

namespace App\Application\Actions\Query;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use App\Application\Actions\Action;
use App\Infrastructure\Query\QueryRepository;
use Psr\Http\Message\ServerRequestInterface as Request;

class ExecuteQueryAction extends Action {
    private $queryRepository;
    private $queries;

    public function __construct(LoggerInterface $logger, QueryRepository $queryRepository) {
        parent::__construct($logger);
        $this->queryRepository = $queryRepository;
        $this->queries = require __DIR__ . '/../../../queries/index.php';
    }

    protected function action(): Response {
        try {
            $request = $this->request->getParsedBody();
            $secret = $this->request->getHeaderLine('Secret');
            $expectedSecret = $_ENV['API_SECRET_TOKEN'];

            // Authenticate request
            if ($secret !== $expectedSecret) {
                return $this->respondWithData(['message' => 'Unauthorized'], 401);
            }

            if (empty($request['token'])) {
                return $this->respondWithData(['message' => 'Missing token'], 400);
            }

            $token = $request['token'];
            if (!array_key_exists($token, $this->queries)) {
                return $this->respondWithData(['message' => 'Invalid token'], 400);
            }

            $parameters = $request['parameters'] ?? [];

            $query = $this->queries[$token];
            $data = $this->queryRepository->executeQuery($query, $parameters);

            return $this->respondWithData($data, 200);
        } catch (\PDOException $e) {
            $this->logger->error('Database error: ' . $e->getMessage(), [
                'exception' => $e,
                'query' => $query ?? '',
                'parameters' => $parameters ?? [],
                'code' => $e->getCode()
            ]);
            return $this->respondWithData(['error' => ['code' => $e->getCode(), 'message' => $e->getMessage()]], 500);
        } catch (\Exception $e) {
            $this->logger->error('Unexpected error: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return $this->respondWithData(['error' => ['message' => $e->getMessage()]], 500);
        }
    }
}
