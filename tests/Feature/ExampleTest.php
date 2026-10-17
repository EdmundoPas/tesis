<?php

namespace Tests\Feature;

use App\Models\Alert;
use App\Models\Attendance;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\ParentModel;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_dashboard_shows_academic_tracking_summary(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('Rendimiento académico')
            ->assertSee('Alertas tempranas')
            ->assertSee('Convivencia escolar')
            ->assertSee('Escuela de padres')
            ->assertSee('Datos de demostración')
            ->assertSee('Ingresar');
    }

    public function test_registration_persists_role_and_parent_relationships_mount_correctly(): void
    {
        $this->withoutExceptionHandling();

        $response = $this->postJson('/api/register', [
            'name' => 'María Apoderada',
            'email' => 'madre@sanluis.edu',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'role' => 'parent',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('users', [
            'email' => 'madre@sanluis.edu',
            'role' => 'parent',
        ]);

        $student = Student::create([
            'user_id' => null,
            'code' => 'STU-1001',
            'grade' => '3',
            'section' => 'A',
            'status' => 'active',
        ]);

        $parent = ParentModel::create([
            'user_id' => User::query()->where('email', 'madre@sanluis.edu')->value('id'),
            'document_number' => '10101010',
            'phone' => '999999999',
        ]);

        $student->parents()->attach($parent->id);

        $this->assertSame([$parent->id], $student->parents()->pluck('parent_models.id')->all());
        $this->assertSame([$student->id], $parent->students()->pluck('students.id')->all());
    }

    public function test_staff_login_requires_the_selected_role_and_opens_the_private_panel(): void
    {
        $admin = User::create([
            'name' => 'Administración',
            'email' => 'admin@sanluis.edu',
            'password' => 'secret123',
            'role' => 'admin',
        ]);

        $this->from('/login')->post('/login', [
            'email' => $admin->email,
            'password' => 'secret123',
            'role' => 'teacher',
        ])->assertRedirect('/login')->assertSessionHasErrors('email');

        $this->post('/login', [
            'email' => $admin->email,
            'password' => 'secret123',
            'role' => 'admin',
        ])->assertRedirect(route('staff.dashboard'));

        $this->assertAuthenticatedAs($admin);
        $this->get(route('staff.dashboard'))->assertOk()->assertSee('Panel administrativo');
        $this->post(route('logout'))->assertRedirect(route('dashboard'));
        $this->assertGuest();
    }

    public function test_admin_panel_shows_live_records_and_parent_chat_while_public_dashboard_stays_generic(): void
    {
        $admin = User::create([
            'name' => 'Administradora',
            'email' => 'admin@sanluis.edu',
            'password' => 'secret123',
            'role' => 'admin',
        ]);
        $parent = User::create([
            'name' => 'Familia Torres',
            'email' => 'familia@sanluis.edu',
            'password' => 'secret123',
            'role' => 'parent',
        ]);
        $studentUser = User::create([
            'name' => 'Estudiante Real',
            'email' => 'estudiante@sanluis.edu',
            'password' => 'secret123',
            'role' => 'student',
        ]);
        $student = Student::create([
            'user_id' => $studentUser->id,
            'code' => 'STU-2001',
            'grade' => '3',
            'section' => 'A',
        ]);
        $course = Course::create([
            'name' => 'Comunicación',
            'grade' => '3',
            'section' => 'A',
        ]);
        $enrollment = Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'period' => 'Bimestre 1',
        ]);
        Grade::create([
            'enrollment_id' => $enrollment->id,
            'period' => 'Bimestre 1',
            'score' => 16.5,
        ]);
        Attendance::create([
            'student_id' => $student->id,
            'date' => '2026-10-15',
            'status' => 'P',
        ]);
        Alert::create([
            'student_id' => $student->id,
            'tipo' => 'academico',
            'nivel' => 'amarillo',
            'descripcion' => 'Revisar seguimiento del periodo',
            'estado' => 'activa',
        ]);
        $room = ChatRoom::create([
            'name' => 'Familias 3.° A',
            'tipo' => 'familias',
            'grado_seccion' => '3.° A',
        ]);
        ChatMessage::create([
            'room_id' => $room->id,
            'user_id' => $parent->id,
            'message' => '¿Cuándo será la próxima reunión?',
        ]);

        $this->actingAs($admin)->get(route('staff.dashboard'))
            ->assertOk()
            ->assertSee('Estudiante Real')
            ->assertSee('16.5')
            ->assertSee('Revisar seguimiento del periodo')
            ->assertSee('¿Cuándo será la próxima reunión?');

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertDontSee('¿Cuándo será la próxima reunión?')
            ->assertDontSee('Estudiante Real');
    }

    public function test_teacher_panel_only_shows_students_from_their_assigned_courses(): void
    {
        $teacherUser = User::create([
            'name' => 'Docente Ana',
            'email' => 'docente@sanluis.edu',
            'password' => 'secret123',
            'role' => 'teacher',
        ]);
        $otherTeacherUser = User::create([
            'name' => 'Docente Luis',
            'email' => 'otro@sanluis.edu',
            'password' => 'secret123',
            'role' => 'teacher',
        ]);
        $teacher = Teacher::create([
            'user_id' => $teacherUser->id,
            'specialty' => 'Matemática',
            'document_number' => '10000001',
        ]);
        $otherTeacher = Teacher::create([
            'user_id' => $otherTeacherUser->id,
            'specialty' => 'Comunicación',
            'document_number' => '10000002',
        ]);
        $course = Course::create([
            'name' => 'Matemática',
            'grade' => '2',
            'section' => 'B',
            'teacher_id' => $teacher->id,
        ]);
        $otherCourse = Course::create([
            'name' => 'Comunicación',
            'grade' => '4',
            'section' => 'A',
            'teacher_id' => $otherTeacher->id,
        ]);
        $ownStudent = Student::create(['code' => 'STU-3001', 'grade' => '2', 'section' => 'B']);
        $otherStudent = Student::create(['code' => 'STU-3002', 'grade' => '4', 'section' => 'A']);
        Enrollment::create(['student_id' => $ownStudent->id, 'course_id' => $course->id, 'period' => 'Bimestre 1']);
        Enrollment::create(['student_id' => $otherStudent->id, 'course_id' => $otherCourse->id, 'period' => 'Bimestre 1']);

        $this->actingAs($teacherUser)->get(route('staff.dashboard'))
            ->assertOk()
            ->assertSee('STU-3001')
            ->assertDontSee('STU-3002')
            ->assertDontSee('Mensajes de las familias');

        $this->getJson('/api/students')
            ->assertOk()
            ->assertJsonFragment(['code' => 'STU-3001'])
            ->assertJsonMissing(['code' => 'STU-3002']);
    }

    public function test_parent_cannot_open_the_staff_dashboard_or_register_as_staff(): void
    {
        $parent = User::create([
            'name' => 'Familia',
            'email' => 'parent@sanluis.edu',
            'password' => 'secret123',
            'role' => 'parent',
        ]);

        $this->actingAs($parent)->get(route('staff.dashboard'))->assertForbidden();
        $this->getJson('/api/students')->assertForbidden();
        $this->getJson('/api/bullying-reports')->assertForbidden();

        $ownStudent = Student::create(['code' => 'STU-4001', 'grade' => '3', 'section' => 'A']);
        $ownProfile = ParentModel::create([
            'user_id' => $parent->id,
            'document_number' => '20000001',
        ]);
        $ownProfile->students()->attach($ownStudent);
        $ownRoom = ChatRoom::create(['name' => 'Familias 3.° A', 'tipo' => 'familias', 'grado_seccion' => '3.° A']);
        $otherRoom = ChatRoom::create(['name' => 'Familias 4.° A', 'tipo' => 'familias', 'grado_seccion' => '4.° A']);

        $this->getJson('/api/chat/rooms')
            ->assertOk()
            ->assertJsonFragment(['name' => 'Familias 3.° A'])
            ->assertJsonMissing(['name' => 'Familias 4.° A']);
        $this->postJson('/api/chat/messages', [
            'room_id' => $otherRoom->id,
            'message' => 'Mensaje fuera de mi grado',
        ])->assertForbidden();
        $this->postJson('/api/chat/messages', [
            'room_id' => $ownRoom->id,
            'message' => 'Mensaje para mi grado',
        ])->assertCreated();

        $this->postJson('/api/register', [
            'name' => 'Admin falso',
            'email' => 'fake-admin@sanluis.edu',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'role' => 'admin',
        ])->assertUnprocessable()->assertJsonValidationErrors('role');
    }
}
