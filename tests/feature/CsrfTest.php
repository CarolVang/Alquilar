<?php

use CodeIgniter\Security\Exceptions\SecurityException;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * Prueba que el filtro CSRF esta activo: un POST sin token valido
 * tiene que ser rechazado ANTES de llegar al controller.
 *
 * @internal
 */
final class CsrfTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testLoginSinTokenEsRechazado(): void
    {
        $this->expectException(SecurityException::class);

        $this->post('login', ['email' => 'a@a.com', 'password' => '123456']);
    }

    public function testReservarSinTokenEsRechazado(): void
    {
        $this->expectException(SecurityException::class);

        $this->post('reservar', [
            'id_herramienta' => 1,
            'fecha_inicio'   => '2026-12-01',
            'fecha_fin'      => '2026-12-02',
        ]);
    }

    public function testPublicarSinTokenEsRechazado(): void
    {
        $this->expectException(SecurityException::class);

        $this->post('publicar-herramienta', ['nombre' => 'Taladro', 'precio' => 100]);
    }
}
