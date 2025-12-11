<?php

namespace App\Exports;

use App\Models\Comment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CommentsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Comment::with(['post', 'user'])
            ->latest()
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Isi Komentar',
            'Artikel',
            'User',
            'Tanggal Dibuat',
            'Tanggal Diupdate'
        ];
    }

    /**
     * @param mixed $comment
     * @return array
     */
    public function map($comment): array
    {
        return [
            $comment->id,
            strip_tags($comment->content),
            $comment->post ? $comment->post->title : 'Artikel dihapus',
            $comment->user ? $comment->user->name : 'User dihapus',
            $comment->created_at->format('d-m-Y H:i'),
            $comment->updated_at->format('d-m-Y H:i')
        ];
    }
}
