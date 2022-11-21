import { defineStore } from 'pinia'

export const useStoreFlagger = defineStore('flagger', {
    state: () => ({
        // flag：個別カテゴリページ（右ページ）と同じカテゴリが削除された場合の感知器
        flag: true
    }),
})
