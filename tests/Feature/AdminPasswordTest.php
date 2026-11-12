<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_bisa_mengganti_password(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'password' => 'password-lama',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.password.update'), [
                'current_password' => 'password-lama',
                'password' => 'password-baru-123',
                'password_confirmation' => 'password-baru-123',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertTrue(Hash::check('password-baru-123', $admin->fresh()->password));
    }

    public function test_password_lama_yang_salah_ditolak(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'password' => 'password-lama',
        ]);

        $this->actingAs($admin)
            ->from(route('admin.password.edit'))
            ->put(route('admin.password.update'), [
                'current_password' => 'password-salah',
                'password' => 'password-baru-123',
                'password_confirmation' => 'password-baru-123',
            ])
            ->assertRedirect(route('admin.password.edit'))
            ->assertSessionHasErrors('current_password');

        $this->assertTrue(Hash::check('password-lama', $admin->fresh()->password));
    }

    public function test_konfirmasi_password_harus_sama(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'password' => 'password-lama',
        ]);

        $this->actingAs($admin)
            ->from(route('admin.password.edit'))
            ->put(route('admin.password.update'), [
                'current_password' => 'password-lama',
                'password' => 'password-baru-123',
                'password_confirmation' => 'berbeda',
            ])
            ->assertRedirect(route('admin.password.edit'))
            ->assertSessionHasErrors('password');

        $this->assertTrue(Hash::check('password-lama', $admin->fresh()->password));
    }

    public function test_tamu_tidak_bisa_membuka_halaman_keamanan(): void
    {
        $this->get(route('admin.password.edit'))
            ->assertRedirect(route('admin.login'));
    }
}
