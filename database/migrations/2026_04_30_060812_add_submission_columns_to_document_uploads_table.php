<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('document_uploads', function (Blueprint $table) {
            // Add submission_type column (file_upload or gdrive_link)
            if (!Schema::hasColumn('document_uploads', 'submission_type')) {
                $table->enum('submission_type', ['file_upload', 'gdrive_link'])->default('file_upload')->after('requirement_id');
            }
            
            // Make file_path nullable (for gdrive_link submissions)
            if (Schema::hasColumn('document_uploads', 'file_path')) {
                $table->string('file_path')->nullable()->change();
            }
            
            // Add submission_value for Google Drive link
            if (!Schema::hasColumn('document_uploads', 'submission_value')) {
                $table->text('submission_value')->nullable()->after('file_path');
            }
            
            // Add file_name column
            if (!Schema::hasColumn('document_uploads', 'file_name')) {
                $table->string('file_name')->nullable()->after('submission_value');
            }
            
            // Change status enum to include more options
            if (Schema::hasColumn('document_uploads', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected', 'incomplete'])->default('pending')->change();
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_uploads', function (Blueprint $table) {
            $table->dropColumn([
                'submission_type',
                'submission_value',
                'file_name',
                'is_reuploaded',
                'reuploaded_at',
                'verification_reason',
                'verification_comment'
            ]);
            
            // Revert file_path back to not nullable
            $table->string('file_path')->nullable(false)->change();
            
            // Revert status back to string
            $table->string('status')->default('Pending')->change();
        });
    }
};