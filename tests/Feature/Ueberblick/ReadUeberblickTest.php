<?php

namespace Tests\Feature\Ueberblick;

use App\Models\Block;
use App\Models\Kurs;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Tests\TestCaseWithBasicData;

class ReadUeberblickTest extends TestCaseWithBasicData {

    public function setUp(): void {
        parent::setUp();

        $this->post('/kurs/' . $this->kursId . '/admin/bloecke', ['full_block_number' => '1.1', 'blockname' => 'Block1', 'datum' => '01.01.2019', 'ma_ids' => null]);
        $this->post('/kurs/' . $this->kursId . '/admin/bloecke', ['full_block_number' => '1.2', 'blockname' => 'Block2', 'datum' => '01.01.2019', 'ma_ids' => null]);
        $this->post('/kurs/' . $this->kursId . '/admin/bloecke', ['full_block_number' => '1.3', 'blockname' => 'Block3', 'datum' => '01.01.2019', 'ma_ids' => null]);
        $this->post('/kurs/' . $this->kursId . '/admin/bloecke', ['full_block_number' => '1.4', 'blockname' => 'Block4', 'datum' => '01.01.2019', 'ma_ids' => null]);
        $this->post('/kurs/' . $this->kursId . '/admin/bloecke', ['full_block_number' => '1.5', 'blockname' => 'Block5', 'datum' => '01.01.2019', 'ma_ids' => null]);
        $this->post('/kurs/' . $this->kursId . '/admin/bloecke', ['full_block_number' => '1.6', 'blockname' => 'Block6', 'datum' => '01.01.2019', 'ma_ids' => null]);
        $this->post('/kurs/' . $this->kursId . '/admin/bloecke', ['full_block_number' => '1.7', 'blockname' => 'Block7', 'datum' => '01.01.2019', 'ma_ids' => null]);
        $this->post('/kurs/' . $this->kursId . '/admin/bloecke', ['full_block_number' => '1.8', 'blockname' => 'Block8', 'datum' => '01.01.2019', 'ma_ids' => null]);
        /** @var User $user */
        $user = Auth::user();
        /** @var Collection $blockIds */
        $blockIds = $user->lastAccessedKurs->bloecke->map(function (Block $block) { return $block->id; });
        $blockIdsToCreateBeobachtungen = $blockIds->sort();
        $blockIdsToCreateBeobachtungen->shift();
        foreach ($blockIdsToCreateBeobachtungen as $blockId) {
            $this->post('/kurs/' . $this->kursId . '/beobachtungen/neu', ['tn_ids' => '' . $this->tnId, 'kommentar' => Block::find($blockId)->blockname, 'bewertung' => '1', 'block_id' => '' . $blockId, 'ma_ids' => '', 'qk_ids' => '']);
        }
    }

    public function test_shouldRequireLogin() {
        // given
        auth()->logout();

        // when
        $response = $this->get('/kurs/' . $this->kursId . '/ueberblick');

        // then
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_shouldDisplayUeberblick() {
        // given

        // when
        $response = $this->get('/kurs/' . $this->kursId . '/ueberblick');

        // then
        $response->assertOk();
        /** @var User $user */
        $user = Auth::user();
        $this->assertSeeAllInOrder('table.table-responsive-cards th', [ 'TN', 'Total', $user->name, '' ]);
        $this->assertSeeAllInOrder('table.table-responsive-cards td', [ 'Pflock', '8', '8', '' ]);
    }

    public function test_shouldNotDisplayUeberblick_toOtherUser() {
        // given
        /** @var User $otherUser */
        $otherUser = factory(User::class)->create();
        $this->be($otherUser);
        $this->post('/neuerkurs', ['name' => 'Zweiter Kurs', 'kursnummer' => '']);

        // when
        $response = $this->get('/kurs/' . $this->kursId . '/ueberblick');

        // then
        $this->assertInstanceOf(ModelNotFoundException::class, $response->exception);
    }
}
