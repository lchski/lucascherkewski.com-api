<?php

namespace Lchski;

use Lchski\Contracts\Controller;
use Slim\Http\Response;

class LinkController extends BaseController implements Controller
{
    /**
     * Get all Links.
     *
     * Path: /links
     *
     * @return Response
     */
    public function index()
    {
        return $this->buildResponse(Link::all());
    }

    /**
     * Create a new Link.
     *
     * Path: /links (POST)
     *
     * @return Response
     */
    public function createSingle()
    {
        $requestBody = $this->request->getParsedBody();

        $item = Link::create($requestBody);

        $item->items()->attach($requestBody['items']);

        return $this->buildResponse($item);
    }

    /**
     * Get a specific Link.
     *
     * Path: /links/{id:[0-9]+}
     *
     * @return Response
     */
    public function getSingle()
    {
        return $this->buildResponse(Link::find((int)$this->args['id']));
    }

    /**
     * Set the content for a specific Link.
     *
     * Writes to a file located in the storage directory: links/{Link->id}.md
     *
     * Path: /links/{id:[0-9]+}/content (POST/PUT)
     *
     * @return Response
     */
    public function setSingleContent()
    {
        $filePath = 'links/' . $this->args['id'] . '.md';

        $writeSuccess = $this->c->storage->put($filePath, $this->request->getParsedBody()['content']);

        if ($writeSuccess) {
            return $this->buildResponse(['content' => $this->c->storage->read($filePath)]);
        }

        return $this->buildResponse(['content' => 'Error: Could not write file.']);
    }

    /**
     * Retrieve the content for a specific Link.
     *
     * The file should be located in the storage directory: links/{Link->id}.md
     *
     * Path: /links/{id:[0-9]+}/content
     *
     * @return Response
     */
    public function getSingleContent()
    {
        $filePath = 'links/' . $this->args['id'] . '.md';

        if ($this->c->storage->has($filePath)) {
            return $this->buildResponse(['content' => $this->c->storage->read($filePath)]);
        }

        return $this->buildResponse(['content' => 'Error: Link content not found.']);
    }

    /**
     * Delete the content for a specific Link.
     *
     * Deletes a file located in the storage directory: links/{Link->id}.md
     *
     * Path: /links/{id:[0-9]+}/content (DELETE)
     *
     * @return Response
     */
    public function deleteSingleContent()
    {
        $filePath = 'links/' . $this->args['id'] . '.md';

        if ($this->c->storage->has($filePath)) {
            return $this->buildResponse($this->c->storage->delete($filePath));
        }

        return $this->buildResponse(['content' => 'Error: Link content not found.']);
    }

    /**
     * Get the Items linked by a specific Link.
     *
     * Path: /links/{id:[0-9]+}/items
     *
     * @return Response
     */
    public function getSingleItems()
    {
        return $this->buildResponse(Link::find((int)$this->args['id'])->items);
    }

    /**
     * Update a specific Link.
     *
     * Path: /links/{id:[0-9]+} (PUT)
     *
     * @return Response
     */
    public function updateSingle()
    {
        $updateSuccess = Link::find((int)$this->args['id'])->update($this->request->getParsedBody());

        if ($updateSuccess) {
            $this->buildResponse(Link::find((int)$this->args['id']));
        }

        return $this->buildResponse('update failed');
    }

    /**
     * Delete a Link.
     *
     * Path: /links/{id:[0-9]+} (DELETE)
     *
     * @return Response
     */
    public function deleteSingle()
    {
        return $this->buildResponse(Link::destroy((int)$this->args['id']));
    }
}
