<?php

use App\Mail\StaffInviteMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

function actingReceptionist(): User
{
    $user = User::factory()->create([
        'role' => 'receptionist',
        'status' => 'active',
        'account_activated' => true,
        'is_first_login' => false,
        'must_change_credentials' => false,
    ]);

    Sanctum::actingAs($user);

    return $user;
}

it('registers a patient without email or contact details', function () {
    Mail::fake();
    actingReceptionist();

    $response = $this->postJson('/api/patients', [
        'firstname' => 'Maria',
        'lastname' => 'Santos',
        'birthdate' => '1992-04-12',
        'sex' => 'female',
        'address' => 'Opol, Misamis Oriental',
    ]);

    $response->assertCreated()
        ->assertJsonPath('user.email', null)
        ->assertJsonPath('user.account_activated', false)
        ->assertJsonPath('user.is_first_login', true)
        ->assertJsonPath('user.must_change_credentials', false)
        ->assertJsonPath('activation.requires_email', true)
        ->assertJsonPath('credentials_emailed', false);

    $patient = User::query()->where('role', 'patient')->firstOrFail();

    expect($patient->email)->toBeNull()
        ->and($patient->password_hash)->toBeNull()
        ->and($patient->status)->toBe('active')
        ->and($patient->account_activated)->toBeFalse()
        ->and($patient->is_first_login)->toBeTrue()
        ->and($patient->must_change_credentials)->toBeFalse();

    Mail::assertNothingQueued();
});

it('still sends credentials when registering a patient with an email', function () {
    Mail::fake();
    actingReceptionist();

    $response = $this->postJson('/api/patients', [
        'firstname' => 'Jose',
        'lastname' => 'Rivera',
        'birthdate' => '1988-10-21',
        'sex' => 'male',
        'email' => 'jose.rivera@example.com',
    ]);

    $response->assertCreated()
        ->assertJsonPath('user.email', 'jose.rivera@example.com')
        ->assertJsonPath('user.account_activated', true)
        ->assertJsonPath('user.must_change_credentials', true)
        ->assertJsonPath('activation.requires_email', false)
        ->assertJsonPath('credentials_emailed', true);

    $patient = User::query()->where('email', 'jose.rivera@example.com')->firstOrFail();

    expect($patient->password_hash)->not->toBeNull()
        ->and(Hash::info($patient->password_hash)['algo'])->not->toBeNull()
        ->and($patient->account_activated)->toBeTrue();

    Mail::assertQueued(StaffInviteMail::class, 1);
});

it('activates the patient portal later after adding an email', function () {
    Mail::fake();
    actingReceptionist();

    $patient = User::factory()->create([
        'role' => 'patient',
        'email' => null,
        'password_hash' => null,
        'status' => 'active',
        'account_activated' => false,
        'is_first_login' => true,
        'must_change_credentials' => false,
    ]);

    $response = $this->postJson("/api/patients/{$patient->user_id}/activate-portal", [
        'email' => 'later.portal@example.com',
    ]);

    $response->assertOk()
        ->assertJsonPath('patient.email', 'later.portal@example.com')
        ->assertJsonPath('patient.account_activated', true)
        ->assertJsonPath('patient.must_change_credentials', true)
        ->assertJsonPath('credentials_emailed', true);

    $patient->refresh();

    expect($patient->email)->toBe('later.portal@example.com')
        ->and($patient->password_hash)->not->toBeNull()
        ->and($patient->account_activated)->toBeTrue()
        ->and($patient->is_first_login)->toBeTrue()
        ->and($patient->must_change_credentials)->toBeTrue();

    Mail::assertQueued(StaffInviteMail::class, function (StaffInviteMail $mail) use ($patient) {
        return $mail->user->is($patient) && $mail->user->email === 'later.portal@example.com';
    });
});

it('prevents reactivating an already activated patient portal', function () {
    Mail::fake();
    actingReceptionist();

    $patient = User::factory()->create([
        'role' => 'patient',
        'email' => 'active.portal@example.com',
        'password_hash' => Hash::make('TempPass123!'),
        'status' => 'active',
        'account_activated' => true,
        'is_first_login' => true,
        'must_change_credentials' => true,
    ]);

    $response = $this->postJson("/api/patients/{$patient->user_id}/activate-portal", [
        'email' => 'new.portal@example.com',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);

    $patient->refresh();

    expect($patient->email)->toBe('active.portal@example.com');

    Mail::assertNothingQueued();
});
