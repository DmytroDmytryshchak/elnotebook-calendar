<?php

class EventController extends Controller
{
    private $eventService;

    public function __construct()
    {
        Auth::handle();

        $this->eventService = new EventService(
            new EventRepository(),
            new EventValidator(),
            new NotificationService(new NotificationRepository())
        );
    }

    // POST /events
    public function store($request)
    {
        try {
            $data  = $request->isJson() ? $request->json() : $request->all();
            $event = $this->eventService->createEvent(
                $this->currentUserId(),
                $data
            );

            $this->json(array(
                'success' => true,
                'event'   => $event->toArray(),
            ), 201);

        } catch (ValidationException $e) {
            $this->json(array(
                'success' => false,
                'errors'  => $e->errors(),
            ), 422);
        }
    }

    // GET /events/{id}
    public function show($request, $id)
    {
        try {
            $event = $this->eventService->getEventById(
                (int) $id,
                $this->currentUserId()
            );

            $this->json(array('success' => true, 'event' => $event->toArray()));

        } catch (NotFoundException $e) {
            $this->json(array('success' => false, 'message' => 'Event not found.'), 404);
        }
    }

    // PUT /events/{id}
    public function update($request, $id)
    {
        try {
            $data  = $request->isJson() ? $request->json() : $request->all();
            $event = $this->eventService->updateEvent(
                (int) $id,
                $this->currentUserId(),
                $data
            );

            $this->json(array('success' => true, 'event' => $event->toArray()));

        } catch (ValidationException $e) {
            $this->json(array('success' => false, 'errors' => $e->errors()), 422);
        } catch (NotFoundException $e) {
            $this->json(array('success' => false, 'message' => 'Event not found.'), 404);
        }
    }

    // DELETE /events/{id}
    public function destroy($request, $id)
    {
        try {
            $this->eventService->deleteEvent(
                (int) $id,
                $this->currentUserId()
            );

            $this->json(array('success' => true));

        } catch (NotFoundException $e) {
            $this->json(array('success' => false, 'message' => 'Event not found.'), 404);
        }
    }
}