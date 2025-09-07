<?php

namespace App\Http\CoreApi\Sandbox\StorageManager;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class StorageController extends  \App\Http\CoreApi\ApiController
{
    private function getStoragePath()
    {
        return storage_path();
    }

    private function getTemplateStoragePath()
    {
        return $this->getStoragePath() . '/storage-sandbox-template';
    }

    public function index()
    {
        $storages = $this->getStorageList();
        $currentStorage = Session::get('sandbox_storage', '1');

        return view('700-page-sandbox.707-page-storage-manager.000-index', compact('storages', 'currentStorage'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'storage_name' => 'required|string|regex:/^[a-zA-Z0-9_-]+$/|max:50',
        ], [
            'storage_name.required' => '스토리지 이름을 입력해주세요.',
            'storage_name.regex' => '영문자, 숫자, 하이픈(-), 언더스코어(_)만 사용 가능합니다.',
            'storage_name.max' => '스토리지 이름은 50자를 초과할 수 없습니다.',
        ]);

        $storageName = $request->storage_name;
        $targetPath = $this->getStoragePath() . '/storage-sandbox-' . $storageName;

        // 이미 존재하는지 확인
        if (File::exists($targetPath)) {
            return back()->withErrors(['storage_name' => '이미 존재하는 스토리지 이름입니다.']);
        }

        // 템플릿이 있는지 확인
        if (!File::exists($this->getTemplateStoragePath())) {
            return back()->with('error', '템플릿 스토리지가 존재하지 않습니다.');
        }

        try {
            // 템플릿 복사
            File::copyDirectory($this->getTemplateStoragePath(), $targetPath);

            return back()->with('success', "스토리지 '{$storageName}'이 성공적으로 생성되었습니다.");
        } catch (\Exception $e) {
            return back()->with('error', '스토리지 생성 중 오류가 발생했습니다: ' . $e->getMessage());
        }
    }

    public function select(Request $request)
    {
        $request->validate([
            'storage_name' => 'required|string',
        ]);

        $storageName = $request->storage_name;
        $storagePath = $this->getStoragePath() . '/storage-sandbox-' . $storageName;

        if (!File::exists($storagePath)) {
            return back()->with('error', '선택하려는 스토리지가 존재하지 않습니다.');
        }

        Session::put('sandbox_storage', $storageName);

        return back()->with('success', "스토리지 '{$storageName}'이 선택되었습니다.");
    }

    public function delete(Request $request)
    {
        $request->validate([
            'storage_name' => 'required|string',
        ]);

        $storageName = $request->storage_name;

        // template은 삭제할 수 없음
        if ($storageName === 'template') {
            return back()->with('error', '템플릿 스토리지는 삭제할 수 없습니다.');
        }

        $storagePath = $this->getStoragePath() . '/storage-sandbox-' . $storageName;

        if (!File::exists($storagePath)) {
            return back()->with('error', '삭제하려는 스토리지가 존재하지 않습니다.');
        }

        // 현재 선택된 스토리지인 경우 기본값으로 변경
        if (Session::get('sandbox_storage') === $storageName) {
            Session::put('sandbox_storage', '1');
        }

        try {
            File::deleteDirectory($storagePath);
            return back()->with('success', "스토리지 '{$storageName}'이 성공적으로 삭제되었습니다.");
        } catch (\Exception $e) {
            return back()->with('error', '스토리지 삭제 중 오류가 발생했습니다: ' . $e->getMessage());
        }
    }

    private function getStorageList()
    {
        $storages = [];
        $storagePath = $this->getStoragePath();

        if (!File::exists($storagePath)) {
            return $storages;
        }

        $directories = File::directories($storagePath);

        foreach ($directories as $directory) {
            $basename = basename($directory);

            if (strpos($basename, 'storage-sandbox-') === 0) {
                $name = substr($basename, strlen('storage-sandbox-'));

                $storages[] = [
                    'name' => $name,
                    'full_path' => $directory,
                    'created_at' => $this->getDirectoryCreatedAt($directory),
                    'size' => $this->getDirectorySize($directory),
                    'file_count' => $this->getFileCount($directory),
                ];
            }
        }

        // 이름순 정렬
        usort($storages, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        return $storages;
    }

    private function getDirectoryCreatedAt($path)
    {
        try {
            return Carbon::createFromTimestamp(filemtime($path))->format('Y-m-d H:i');
        } catch (\Exception $e) {
            return '알 수 없음';
        }
    }

    private function getDirectorySize($path)
    {
        try {
            $size = $this->getDirectorySizeRecursive($path);
            return $this->formatBytes($size);
        } catch (\Exception $e) {
            return '알 수 없음';
        }
    }

    private function getDirectorySizeRecursive($path)
    {
        $size = 0;

        if (is_dir($path)) {
            $files = File::allFiles($path);
            foreach ($files as $file) {
                $size += $file->getSize();
            }
        }

        return $size;
    }

    private function getFileCount($path)
    {
        try {
            return count(File::allFiles($path));
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function formatBytes($size)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = 0;

        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }

        return round($size, 1) . ' ' . $units[$unitIndex];
    }
}
