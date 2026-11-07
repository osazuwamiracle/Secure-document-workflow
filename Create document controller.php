
<?php
// Secure Document Approval - Separation of Duties
// No user can approve their own document. This stops insider fraud.

class DocumentController {

  public function approve(Request $request, $documentId) {
    $doc = Document::findOrFail($documentId);

    // SECURITY 1: Creator cannot approve own document
    if ($doc->created_by === $request->user()->id) {
      AuditLogger::log($request->user()->id, 'APPROVE_BLOCKED_SELF', ['doc' => $documentId]);
      return response()->json(['error' => 'Cannot approve your own document'], 403);
    }

    // SECURITY 2: Must have approver role (RBAC)
    if (!$request->user()->hasRole('approver')) {
      AuditLogger::log($request->user()->id, 'DENIED_APPROVE', ['doc' => $documentId]);
      abort(403, 'Unauthorized');
    }

    // SECURITY 3: Only Submitted / Reviewed docs can be approved
    if (!in_array($doc->status, ['submitted', 'reviewed'])) {
      return response()->json(['error' => 'Document not ready for approval'], 400);
    }

    $doc->status = 'approved';
    $doc->approved_by = $request->user()->id;
    $doc->save();

    AuditLogger::log($request->user()->id, 'APPROVED', ['doc' => $documentId]);

    return response()->json(['message' => 'Document approved']);
  }
}
