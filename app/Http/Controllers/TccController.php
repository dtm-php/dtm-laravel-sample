<?php

namespace App\Http\Controllers;

use DtmClient\Api\ApiInterface;
use DtmClient\TCC;
use DtmClient\TransContext;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class TccController extends AbstractController
{
    protected TCC $tcc;

    protected ApiInterface $api;

    public function __construct(TCC $tcc, ApiInterface $api)
    {
        $this->tcc = $tcc;
        $this->api = $api;
    }

    // 定义测试类，返回hello world
    public function test()
    {
        return 'hello world';
    }

    // 获取tcc gid
    public function getGid()
    {
        $gid = $this->tcc->generateGid();
        return response()->json(['gid' => $gid]);
    }


    public function successCase()
    {
        try {
            $gid = $this->tcc->generateGid();

            $this->tcc->globalTransaction(function (TCC $tcc) {
                $tcc->callBranch(
                    ['trans_name' => 'trans_A'],
                    'http://127.0.0.1:8000/api' . '/tcc/transA/try',
                    'http://127.0.0.1:8000/api' . '/tcc/transA/confirm',
                    'http://127.0.0.1:8000/api' . '/tcc/transA/cancel'
                );

                $tcc->callBranch(
                    ['trans_name' => 'trans_B'],
                    'http://127.0.0.1:8000/api' . '/tcc/transB/try',
                    'http://127.0.0.1:8000/api' . '/tcc/transB/confirm',
                    'http://127.0.0.1:8000/api' . '/tcc/transB/cancel'
                );
            }, $gid);
        } catch (Throwable $e) {
//            dd($e->getMessage());
            return response()->json(['error' => $e->getMessage()]);
        }
        return response()->json(['gid' => TransContext::getGid()]);
    }

    public function queryAllCase()
    {
        $result = $this->api->queryAll(['last_id' => '']);
        return response()->json(['data' => $result]);

    }

    public function rollbackCase()
    {
        try {
            $this->tcc->globalTransaction(function (TCC $tcc) {
                $tcc->callBranch(
                    ['trans_name' => 'trans_A'],
                    'http://127.0.0.1:8000/api' . '/tcc/transA/try',
                    'http://127.0.0.1:8000/api' . '/tcc/transA/confirm',
                    'http://127.0.0.1:8000/api' . '/tcc/transA/cancel'
                );

                $tcc->callBranch(
                    ['trans_name' => 'trans_B'],
                    'http://127.0.0.1:8000/api' . '/tcc/transB/try/fail',
                    'http://127.0.0.1:8000/api' . '/tcc/transB/confirm',
                    'http://127.0.0.1:8000/api' . '/tcc/transB/cancel'
                );
            });
        } catch (Throwable $exception) {
            // Do Nothing
        }
    }

    public function transATry()
    {
        return response()->json([
            'dtm_result' => 'SUCCESS',
        ]);
    }

    public function transAConfirm()
    {
        return response()->json([
            'dtm_result' => 'SUCCESS',
        ]);
    }

    public function transACancel()
    {
        return response()->json([
            'dtm_result' => 'SUCCESS',
        ]);
    }

    public function transBTry()
    {
        return response()->json([
            'dtm_result' => 'SUCCESS',
        ]);
    }

    public function transBTryFail()
    {
        return response()->json([
            'dtm_result' => 'FAIL',
        ], 409);
    }

    public function transBConfirm()
    {
        return response()->json([
            'dtm_result' => 'SUCCESS',
        ]);
    }

    public function transBCancel()
    {
        return response()->json([
            'dtm_result' => 'SUCCESS',
        ]);
    }


}
