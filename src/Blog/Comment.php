<?php

namespace Geekbrains\LevelTwo\Blog;

class Comment
{

    private int $id;
    private User $author;
    private Post $post;
    private string $comment;

    /**
     * @param int $id
     * @param User $author
     * @param Post $post
     * @param string $comment
     */
    public function __construct(int $id, User $author, Post $post, string $comment)
    {
        $this->id = $id;
        $this->author = $author;
        $this->post = $post;
        $this->comment = $comment;
    }

    public function __toString() {
        return "$this->author оставил комментарий на статью <<{$this->post->getTitle()}>> автора <<{$this->post->getAuthor()->getName()}>> Комментарий таков: <<$this->comment>>";
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param User $author
     */
    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    /**
     * @return Post
     */
    public function getPost(): Post
    {
        return $this->post;
    }

    /**
     * @param Post $post
     */
    public function setPost(Post $post): void
    {
        $this->post = $post;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }


}