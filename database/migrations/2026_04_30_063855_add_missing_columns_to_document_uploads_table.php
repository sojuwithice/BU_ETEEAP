<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('document_uploads', function (Blueprint $table) {
            // Add submission_type column if not exists
            if (!Schema::hasColumn('document_uploads', 'submission_type')) {
                $table->enum('submission_type', ['file_upload', 'gdrive_link'])->default('file_upload')->after('requirement_id');
            }
            
            // Add submission_value column for Google Drive link
            if (!Schema::hasColumn('document_uploads', 'submission_value')) {
                $table->text('submission_value')->nullable()->after('file_path');
            }
            
            // Add file_name column
            if (!Schema::hasColumn('document_uploads', 'file_name')) {
                $table->string('file_name')->nullable()->after('file_path');
            }
            
            // Add is_reuploaded column
            if (!Schema::hasColumn('document_uploads', 'is_reuploaded')) {
                $table->boolean('is_reuploaded')->default(false)->after('status');
            }
            
            // Add reuploaded_at column
            if (!Schema::hasColumn('document_uploads', 'reuploaded_at')) {
                $table->timestamp('reuploaded_at')->nullable()->after('is_reuploaded');
            }
            
            // Add verification_reason column
            if (!Schema::hasColumn('document_uploads', 'verification_reason')) {
                $table->text('verification_reason')->nullable()->after('reuploaded_at');
            }
            
            // Add verification_comment column
            if (!Schema::hasColumn('document_uploads', 'verification_comment')) {
                $table->text('verification_comment')->nullable()->after('verification_reason');
            }
            
            // Make file_path nullable (for gdrive_link submissions)
            if (Schema::hasColumn('document_uploads', 'file_path')) {
                $table->string('file_path')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('document_uploads', function (Blueprint $table) {
            $columns = ['submission_type', 'submission_value', 'file_name', 'is_reuploaded', 'reuploaded_at', 'verification_reason', 'verification_comment'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('document_uploads', $column)) {
                    $table->dropColumn($column);
                }
            }
            
            // Revert file_path back to not nullable
            $table->string('file_path')->nullable(false)->change();
        });
    }
};