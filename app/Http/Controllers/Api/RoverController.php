<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RoverService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use Illuminate\Validation\ValidationException;

class RoverController extends Controller
{
    /** @var RoverService $roverService */
    protected $roverService;
   
    /**
     * Constructor
     *
     * @param RoverService $roverService
     */
    public function __construct(RoverService $roverService)
    {
        $this->roverService = $roverService;
    }

    /**
     * Execute movement
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function move(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'commands' => 'required|string|regex:/^[FRL]*$/',
                'start_x' => 'required|integer',
                'start_y' => 'required|integer',
                'direction' => 'required|string|in:N,E,S,W',
            ]);

            $this->roverService = new RoverService(
                $request->input('start_x'), 
                $request->input('start_y'), 
                $request->input('direction')
            );

            $position = $this->roverService->move($request->input('commands'));

            $movementResponse =  [
                'commands_send' => $request->input('commands'),
                'commands_executed' => implode('', $this->roverService->getExecutedCommands()),
                'from' => [
                    'x' => (int)$request->input('start_x'),
                    'y' => (int)$request->input('start_y'),
                    'direction' => $request->input('direction'),
                ],
                'to' => [
                    'x' => $position['x'],
                    'y' => $position['y'],
                    'direction' => $position['direction'],
                ],
                'obstacle' => $position['obstacle']
            ];

            return response()->json($movementResponse, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An unexpected error occurred',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
