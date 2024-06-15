<?php

namespace App\Http\Controllers\Base;

use App\Http\Resources\Base\BaseDataCollection;
use App\Http\Resources\Base\BaseDataResource;
use App\Services\_Exception\AppServiceException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use function resolve;
use function response;
use const HTTP_VALIDATE_FAIL;

trait BaseEntryDataTrait
{
    // baseDataCollection => class name list data
    // baseDataSelectCollection => class name list data select
    // searchRequestClass => class name to validate INDEX request.
    // showRequestClass => class name to validate SHOW request.
    // saveRequestClass => class name to validate STORE and UPDATE request.
    // storeRequestClass => class name to validate STORE request.
    // updateRequestClass => class name to validate UPDATE request.
    // destroyRequestClass => class name to validate DESTROY request.


    public function index(Request $request)
    {
        if (isset($this->searchRequestClass)) {
            $request = resolve($this->searchRequestClass);
        }
        $entries = $this->entryService->search();
        if (isset($this->baseDataCollection)) {
            $class = $this->baseDataCollection;
            return new $class($entries);
        }
        return new BaseDataCollection($entries);
    }



    public function destroy($entryId, Request $request)
    {
        try {
            if (isset($this->destroyRequestClass)) {
                $request = resolve($this->destroyRequestClass);
            }
            $this->entryService->delete($entryId);
            return response()->json([]);
        } catch (AppServiceException|\Exception|HttpResponseException $e)
        {
            return $e->getMessage() ?  response()->json([
                'error_msg' => $e->getMessage(),
            ], $e->getCode() ?: HTTP_VALIDATE_FAIL) : response()->json(json_decode($e->getResponse()->getContent()), $e->getCode() ?
                : HTTP_VALIDATE_FAIL);
        }
    }

    public function show($id)
    {
        if (isset($this->showRequestClass)) {
            $request = resolve($this->showRequestClass);
        }
        try {
            $entry = $this->entryService->find($id);
            if (isset($this->baseDataResource)) {
                $class = $this->baseDataResource;
                return new $class($entry);
            }
            return response()->json([
                'data' => new BaseDataResource($entry)
            ]);
        }
        catch (AppServiceException|\Exception|HttpResponseException $e)
        {
            return $e->getMessage() ?  response()->json([
                'error_msg' => $e->getMessage(),
            ], $e->getCode() ?: HTTP_VALIDATE_FAIL) :  response()->json(json_decode($e->getResponse()->getContent()), $e->getCode() ?
                : HTTP_VALIDATE_FAIL);
        }
    }

    public function store(Request $request)
    {
        if (isset($this->saveRequestClass)) {
            $request = resolve($this->saveRequestClass);
        } else if (isset($this->createRequestClass)) {
            $request = resolve($this->createRequestClass);
        }
        try {
            $entry = $this->entryService->createFromRequest($request);
            if (isset($this->baseDataResource)) {
                $class = $this->baseDataResource;
                return new $class($entry);
            }
            return new BaseDataResource($entry);
        }
        catch (AppServiceException|\Exception|HttpResponseException $e)
        {
            return $e->getMessage() ?  response()->json([
                'error_msg' => $e->getMessage(),
            ], $e->getCode() ?: HTTP_VALIDATE_FAIL) :  response()->json(json_decode($e->getResponse()->getContent()), $e->getCode() ?
                : HTTP_VALIDATE_FAIL);;
        }
    }

    public function update($entryId, Request $request)
    {
        try {
            if (isset($this->saveRequestClass)) {
                $request = resolve($this->saveRequestClass);
            } else if (isset($this->updateRequestClass)) {
                $request = resolve($this->updateRequestClass);
            }
            if(isset($this->default) && isset($this->notDefault) && $request->default == $this->default) {
                $this->entryService->updateNotDefault($this->default, $this->notDefault);
            }
            $entry = $this->entryService->find($entryId);
            $this->entryService->updateFromRequest($entryId, $request);
            if ($entry) {
                $entry = $entry->fresh();
            }
            if (isset($this->baseDataResource)) {
                $class = $this->baseDataResource;
                return new $class($entry);
            }

            return new BaseDataResource($entry);
        } catch (AppServiceException|\Exception|HttpResponseException $e)
        {
            return $e->getMessage() ?  response()->json([
                'error_msg' => $e->getMessage(),
            ], $e->getCode() ?: HTTP_VALIDATE_FAIL) :  response()->json(json_decode($e->getResponse()->getContent()), $e->getCode() ?
                : HTTP_VALIDATE_FAIL);
        }
    }

    public function storeAdmin(Request $request)
    {
        if (isset($this->saveRequestClass)) {
            $request = resolve($this->saveRequestClass);
        } else if (isset($this->createRequestClass)) {
            $request = resolve($this->createRequestClass);
        }
        try {
            $entry = $this->entryService->createFromRequestAdmin($request);
            if (isset($this->baseDataResource)) {
                $class = $this->baseDataResource;
                return new $class($entry);
            }
            return new BaseDataResource($entry);
        }
        catch (AppServiceException|\Exception|HttpResponseException $e)
        {
            return $e->getMessage() ?  response()->json([
                'error_msg' => $e->getMessage(),
            ], $e->getCode() ?: HTTP_VALIDATE_FAIL) :  response()->json(json_decode($e->getResponse()->getContent()), $e->getCode() ?
                : HTTP_VALIDATE_FAIL);;
        }
    }

    public function updateAdmin($entryId, Request $request)
    {
        try {
            if (isset($this->saveRequestClass)) {
                $request = resolve($this->saveRequestClass);
            } else if (isset($this->updateRequestClass)) {
                $request = resolve($this->updateRequestClass);
            }
            $entry = $this->entryService->find($entryId);
            $this->entryService->updateFromRequestAdmin($entryId, $request);
            if ($entry) {
                $entry = $entry->fresh();
            }
            if (isset($this->baseDataResource)) {
                $class = $this->baseDataResource;
                return new $class($entry);
            }

            return new BaseDataResource($entry);
        } catch (AppServiceException|\Exception|HttpResponseException $e)
        {
            return $e->getMessage() ?  response()->json([
                'error_msg' => $e->getMessage(),
            ], $e->getCode() ?: HTTP_VALIDATE_FAIL) :  response()->json(json_decode($e->getResponse()->getContent()), $e->getCode() ?
                : HTTP_VALIDATE_FAIL);
        }
    }

    public function storeUser(Request $request)
    {
        if (isset($this->saveRequestClass)) {
            $request = resolve($this->saveRequestClass);
        } else if (isset($this->createRequestClass)) {
            $request = resolve($this->createRequestClass);
        }
        try {
            $entry = $this->entryService->createFromRequestUser($request);
            if (isset($this->baseDataResource)) {
                $class = $this->baseDataResource;
                return new $class($entry);
            }
            return new BaseDataResource($entry);
        }
        catch (AppServiceException|\Exception|HttpResponseException $e)
        {
            return $e->getMessage() ?  response()->json([
                'error_msg' => $e->getMessage(),
            ], $e->getCode() ?: HTTP_VALIDATE_FAIL) :  response()->json(json_decode($e->getResponse()->getContent()), $e->getCode() ?
                : HTTP_VALIDATE_FAIL);;
        }
    }

    public function updateUser($entryId, Request $request)
    {
        try {
            if (isset($this->saveRequestClass)) {
                $request = resolve($this->saveRequestClass);
            } else if (isset($this->updateRequestClass)) {
                $request = resolve($this->updateRequestClass);
            }
            $entry = $this->entryService->find($entryId);
            $this->entryService->updateFromRequestUser($entryId, $request);
            if ($entry) {
                $entry = $entry->fresh();
            }
            if (isset($this->baseDataResource)) {
                $class = $this->baseDataResource;
                return new $class($entry);
            }

            return new BaseDataResource($entry);
        } catch (AppServiceException|\Exception|HttpResponseException $e)
        {
            return $e->getMessage() ?  response()->json([
                'error_msg' => $e->getMessage(),
            ], $e->getCode() ?: HTTP_VALIDATE_FAIL) :  response()->json(json_decode($e->getResponse()->getContent()), $e->getCode() ?
                : HTTP_VALIDATE_FAIL);
        }
    }
}
