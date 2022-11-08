#!/usr/bin/env bash

cd $GITHUB_WORKSPACE

COMMIT_ID=$(cat $GITHUB_EVENT_PATH | jq -r '.pull_request.head.sha')

echo "COMMIT ID: $COMMIT_ID"

PR_BODY=$(cat "$GITHUB_EVENT_PATH" | jq -r .pull_request.body)
if [[ "$PR_BODY" == *"[reference-url]"* ]]; then
  echo "[reference-url] found in PR description."
  exit 0
fi

