<?php

namespace prgTW\BaseCRM\Tests\Service;

use prgTW\BaseCRM\BaseCrm;
use prgTW\BaseCRM\Client\GuzzleClient;
use prgTW\BaseCRM\Resource\Resource;
use prgTW\BaseCRM\Service\Contact;
use prgTW\BaseCRM\Service\Note;
use prgTW\BaseCRM\Tests\AbstractTest;

class NotesTest extends AbstractTest
{
	public function testGet()
	{
		$client = \Mockery::mock(GuzzleClient::class);
		$client
			->shouldReceive('request')
			->once()
			->with('GET', sprintf('%s/%s/contacts/1/notes.json', Resource::ENDPOINT_SALES, Resource::PREFIX), $this->getQuery())
			->andReturn($this->getResponse(200, '
				[
					{
						"note": {
							"created_at": "2011-08-19T15:54:16Z",
							"updated_at": "2011-08-19T15:54:16Z",
							"contact_id": 579,
							"username": "User 1",
							"id": 89,
							"content": "Some note",
							"deal_id": 0
						}
					}
				]
			'));

		$baseCrm = new BaseCrm('', $client);
		/** @var Contact $contact */
		$contact = $baseCrm->getContacts()->get(1);
		$notes   = $contact->getNotes();
		$this->assertCount(1, $notes);
		$note = $notes[0];
		$this->assertInstanceOf(Note::class, $note);
		$this->assertEquals('2011-08-19T15:54:16Z', $note->created_at);
		$this->assertEquals('2011-08-19T15:54:16Z', $note->updated_at);
		$this->assertEquals(579, $note->contact_id);
		$this->assertEquals('User 1', $note->username);
		$this->assertEquals(89, $note->id);
		$this->assertEquals("Some note", $note->content);
		$this->assertEquals(0, $note->deal_id);
	}
}
