<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class UserController extends Controller
{
    protected $client;
    protected $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->baseUrl = 'http://localhost:8080';
    }

    public function index()
    {
        try {
            $response = $this->client->get("{$this->baseUrl}/user");
            $data = json_decode($response->getBody(), true);

            return view('admin.user.index', ['user' => $data]);
        } catch (RequestException $e) {
            return back()->with('error', 'Failed to fetch data: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|string',
            'username' => 'required|string',
            'password' => 'required|string',
            'level' => 'required|string'
        ]);

        try {
            $this->client->post("{$this->baseUrl}/user", [
                'form_params' => $request->only(['id_user', 'username', 'password', 'level']),
            ]);

            return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan');
        } catch (RequestException $e) {
            return back()->with('error', 'Failed to add data: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $response = $this->client->get("{$this->baseUrl}/user/{$id}");
            $data = json_decode($response->getBody(), true);

            return view('admin.user.edit', ['user' => $data]);
        } catch (RequestException $e) {
            return back()->with('error', 'Failed to fetch data: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_user' => 'required|string',
            'username' => 'required|string',
            'password' => 'required|string',
            'level' => 'required|string'
        ]);

        try {
            $this->client->put("{$this->baseUrl}/user/{$id}", [
                'form_params' => $request->only(['id_user', 'username', 'password', 'level']),
            ]);

            return redirect()->route('user.index')->with('success', 'User berhasil diperbarui');
        } catch (RequestException $e) {
            return back()->with('error', 'Failed to update data: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $this->client->delete("{$this->baseUrl}/user/{$id}");

            return redirect()->route('user.index')->with('success', 'User berhasil dihapus');
        } catch (RequestException $e) {
            return back()->with('error', 'Failed to delete data: ' . $e->getMessage());
        }
    }
}
