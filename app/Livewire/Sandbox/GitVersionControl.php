<?php

namespace App\Livewire\Sandbox;

use Livewire\Component;

class GitVersionControl extends Component
{
    public $activeTab = 'repository';
    public $repositoryStatus = '';
    public $branches = [];
    public $currentBranch = '';
    public $commits = [];
    public $workingDirectory = '';
    public $uncommittedFiles = [];
    public $selectedFiles = [];
    public $commitMessage = '';
    public $newBranchName = '';
    public $mergeFromBranch = '';
    public $cloneUrl = '';

    public function mount()
    {
        $this->workingDirectory = getcwd();
        $this->refreshStatus();
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function refreshStatus()
    {
        if ($this->isGitRepository()) {
            $this->getCurrentBranch();
            $this->getBranches();
            $this->getUncommittedFiles();
            $this->getCommitHistory();
        }
    }

    private function isGitRepository()
    {
        return is_dir('.git') || (bool) exec('git rev-parse --is-inside-work-tree 2>/dev/null');
    }

    private function getCurrentBranch()
    {
        $this->currentBranch = trim(exec('git branch --show-current 2>/dev/null'));
    }

    private function getBranches()
    {
        $output = [];
        exec('git branch -a 2>/dev/null', $output);
        $this->branches = array_map(function($branch) {
            return trim(str_replace(['*', 'origin/', 'remotes/'], '', $branch));
        }, $output);
        $this->branches = array_unique(array_filter($this->branches));
    }

    private function getUncommittedFiles()
    {
        $output = [];
        exec('git status --porcelain 2>/dev/null', $output);
        $this->uncommittedFiles = array_map(function($line) {
            $status = substr($line, 0, 2);
            $file = trim(substr($line, 2));
            return [
                'file' => $file,
                'status' => $this->getStatusText($status)
            ];
        }, $output);
    }

    private function getCommitHistory()
    {
        $output = [];
        exec('git log --oneline -10 2>/dev/null', $output);
        $this->commits = array_map(function($line) {
            $parts = explode(' ', $line, 2);
            return [
                'hash' => $parts[0] ?? '',
                'message' => $parts[1] ?? ''
            ];
        }, $output);
    }

    private function getStatusText($status)
    {
        $statusMap = [
            'M ' => 'Modified',
            'A ' => 'Added',
            'D ' => 'Deleted',
            'R ' => 'Renamed',
            'C ' => 'Copied',
            'U ' => 'Updated but unmerged',
            '??' => 'Untracked',
            ' M' => 'Modified (not staged)',
            ' D' => 'Deleted (not staged)',
        ];
        return $statusMap[$status] ?? 'Unknown';
    }

    public function initRepository()
    {
        exec('git init', $output, $returnVar);
        if ($returnVar === 0) {
            session()->flash('message', 'Git repository initialized successfully.');
            $this->refreshStatus();
        } else {
            session()->flash('error', 'Failed to initialize Git repository.');
        }
    }

    public function addFiles()
    {
        if (empty($this->selectedFiles)) {
            session()->flash('error', 'No files selected.');
            return;
        }

        $files = implode(' ', array_map('escapeshellarg', $this->selectedFiles));
        exec("git add {$files}", $output, $returnVar);
        
        if ($returnVar === 0) {
            session()->flash('message', 'Files staged successfully.');
            $this->selectedFiles = [];
            $this->refreshStatus();
        } else {
            session()->flash('error', 'Failed to stage files.');
        }
    }

    public function commitChanges()
    {
        if (empty($this->commitMessage)) {
            session()->flash('error', 'Commit message is required.');
            return;
        }

        $message = escapeshellarg($this->commitMessage);
        exec("git commit -m {$message}", $output, $returnVar);
        
        if ($returnVar === 0) {
            session()->flash('message', 'Changes committed successfully.');
            $this->commitMessage = '';
            $this->refreshStatus();
        } else {
            session()->flash('error', 'Failed to commit changes.');
        }
    }

    public function createBranch()
    {
        if (empty($this->newBranchName)) {
            session()->flash('error', 'Branch name is required.');
            return;
        }

        $branchName = escapeshellarg($this->newBranchName);
        exec("git checkout -b {$branchName}", $output, $returnVar);
        
        if ($returnVar === 0) {
            session()->flash('message', "Branch '{$this->newBranchName}' created and switched to.");
            $this->newBranchName = '';
            $this->refreshStatus();
        } else {
            session()->flash('error', 'Failed to create branch.');
        }
    }

    public function switchBranch($branchName)
    {
        $branch = escapeshellarg($branchName);
        exec("git checkout {$branch}", $output, $returnVar);
        
        if ($returnVar === 0) {
            session()->flash('message', "Switched to branch '{$branchName}'.");
            $this->refreshStatus();
        } else {
            session()->flash('error', "Failed to switch to branch '{$branchName}'.");
        }
    }

    public function mergeBranch()
    {
        if (empty($this->mergeFromBranch)) {
            session()->flash('error', 'Source branch is required.');
            return;
        }

        $branch = escapeshellarg($this->mergeFromBranch);
        exec("git merge {$branch}", $output, $returnVar);
        
        if ($returnVar === 0) {
            session()->flash('message', "Merged '{$this->mergeFromBranch}' into current branch.");
            $this->mergeFromBranch = '';
            $this->refreshStatus();
        } else {
            session()->flash('error', 'Failed to merge branch.');
        }
    }

    public function cloneRepository()
    {
        if (empty($this->cloneUrl)) {
            session()->flash('error', 'Repository URL is required.');
            return;
        }

        $url = escapeshellarg($this->cloneUrl);
        $cloneDir = 'cloned-repo-' . time();
        exec("git clone {$url} {$cloneDir}", $output, $returnVar);
        
        if ($returnVar === 0) {
            session()->flash('message', "Repository cloned to '{$cloneDir}' directory.");
            $this->cloneUrl = '';
        } else {
            session()->flash('error', 'Failed to clone repository.');
        }
    }

    public function render()
    {
        return view('700-page-sandbox.700-livewire-git-version-control');
    }
}