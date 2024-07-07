<?php

describe('Token creation tests', function () {
    it('should create a valid access token', function () {
        $jwtService = jwtService();
        $tokenParts = explode('.', $jwtService->createAccessToken([])->toString());
        $this->assertCount(3, $tokenParts);

        list($header, $payload, $signature) = $tokenParts;

        $this->assertTrue(isBase64($header));
        $this->assertTrue(isBase64($payload));
        $this->assertTrue(isBase64($signature));
    });

    it('should create a valid refresh token', function () {
        $jwtService = jwtService();
        $tokenParts = explode('.', $jwtService->createRefreshToken([])->toString());
        $this->assertCount(3, $tokenParts);

        list($header, $payload, $signature) = $tokenParts;

        $this->assertTrue(isBase64($header));
        $this->assertTrue(isBase64($payload));
        $this->assertTrue(isBase64($signature));
    });
});

describe('Token verification tests', function () {
    it('should correctly verify a valid access token', function () {
        $jwtService = jwtService();

        $token = $jwtService->createAccessToken([])->toString();

        expect($jwtService->verifyToken($token))->toBeTrue();
    });

    it('should correctly parse a valid access token', function () {
        $jwtService = jwtService();

        $token = $jwtService->createAccessToken([])->toString();
        $parsedToken = $jwtService->parseToken($token);
        expect($parsedToken)->toBeInstanceOf(\Lcobucci\JWT\Token::class);
    });

    it('should correctly verify a valid refresh token', function () {
        $jwtService = jwtService();

        $token = $jwtService->createAccessToken([])->toString();

        expect($jwtService->verifyToken($token))->toBeTrue();
    });

    it('should correctly parse a valid refresh token', function () {
        $jwtService = jwtService();

        $token = $jwtService->createAccessToken([])->toString();
        $parsedToken = $jwtService->parseToken($token);
        expect($parsedToken)->toBeInstanceOf(\Lcobucci\JWT\Token::class);
    });
});
