<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfileReactionModel extends Model
{
    protected $table = 'profile_reactions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['reactor_id', 'profile_id', 'type'];
    protected $useTimestamps = true;
    
    /**
     * Add or update a reaction
     */
    public function react($reactorId, $profileId, $type)
    {
        $existing = $this->where('reactor_id', $reactorId)
                         ->where('profile_id', $profileId)
                         ->first();
        
        if ($existing) {
            if ($existing['type'] === $type) {
                // Remove reaction (unlike/unlove)
                $this->delete($existing['id']);
                return ['action' => 'removed', 'type' => $type];
            } else {
                // Change reaction type
                $this->update($existing['id'], ['type' => $type]);
                return ['action' => 'changed', 'type' => $type];
            }
        } else {
            // Add new reaction
            $this->insert([
                'reactor_id' => $reactorId,
                'profile_id' => $profileId,
                'type' => $type
            ]);
            return ['action' => 'added', 'type' => $type];
        }
    }
    
    /**
     * Get all reactions for a profile
     */
    public function getReactionsForProfile($profileId)
    {
        $reactions = $this->where('profile_id', $profileId)->findAll();
        
        $result = [
            'like_count' => 0,
            'love_count' => 0,
            'total_count' => 0,
            'user_reaction' => null
        ];
        
        foreach ($reactions as $reaction) {
            if ($reaction['type'] === 'like') {
                $result['like_count']++;
            } elseif ($reaction['type'] === 'love') {
                $result['love_count']++;
            }
            $result['total_count']++;
        }
        
        return $result;
    }
    
    /**
     * Get user's reaction to a profile
     */
    public function getUserReaction($reactorId, $profileId)
    {
        $reaction = $this->where('reactor_id', $reactorId)
                         ->where('profile_id', $profileId)
                         ->first();
        
        return $reaction ? $reaction['type'] : null;
    }
    
    /**
     * Get recent reactions for notification
     */
    public function getRecentReactions($profileId, $limit = 10)
    {
        return $this->select('profile_reactions.*, members.first_name, members.last_name, members.profile_photo')
                    ->join('members', 'members.id = profile_reactions.reactor_id')
                    ->where('profile_reactions.profile_id', $profileId)
                    ->orderBy('profile_reactions.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}