<template>
    <div v-if="!item">در حال بارگذاری یا موردی یافت نشد</div>
    <div v-else class="cartable-details">
        <section class="news-section">
            <div class="news-header">
                <div v-if="item.has_rule" class="user-info over-news">
                    <img v-if="item.user?.image" :src="item.user.image" class="avatar" alt="User" />
                    <svg v-else class="avatar" xmlns="http://www.w3.org/2000/svg" fill="#000000" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><g data-v-ab5c279c=""><rect data-v-ab5c279c="" fill="none" height="24" width="24"></rect></g><g data-v-ab5c279c=""><path data-v-ab5c279c="" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm0 14c-2.03 0-4.43-.82-6.14-2.88C7.55 15.8 9.68 15 12 15s4.45.8 6.14 2.12C16.43 19.18 14.03 20 12 20z"></path></g></svg>
                    <div class="user">
                        <strong>{{ item.user.name }} {{ item.user.family }}</strong>
                        <a v-if="item.user?.phone" :href="`tel:${item.user.phone}`">
                            <svg height="25" width="25" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0,0,256,256" data-v-ac41ac96=""><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal;" data-v-ac41ac96=""><g transform="scale(4,4)" data-v-ac41ac96=""><path d="M1,32c0,-17.1 13.9,-31 31,-31c17.1,0 31,13.9 31,31c0,17.1 -13.9,31 -31,31c-17.1,0 -31,-13.9 -31,-31z" fill="#3ec81c" data-v-ac41ac96=""></path><path d="M43.1,51.4c-0.7,0 -1.3,-0.1 -1.9,-0.4c-3.6,-1.6 -10.6,-5.1 -16.8,-11.3c-6.2,-6.2 -9.8,-13.3 -11.4,-16.9c-0.8,-1.8 -0.4,-3.9 1.1,-5.4l3.8,-3.8c0.5,-0.5 1.1,-0.9 1.8,-1c1,-0.2 2,0.2 2.7,0.9l4.9,4.9c1.3,1.3 1.3,3.4 0,4.8l-3.9,3.9c-0.4,0.4 -0.5,1 -0.3,1.5c0.7,1.5 2.3,4.6 5,7.3c2.7,2.7 5.8,4.3 7.3,5c0.5,0.2 1.1,0.1 1.5,-0.3l4,-4c0.5,-0.5 1,-0.8 1.7,-0.9c1,-0.2 2,0.1 2.8,0.9l5.1,5.1c1.2,1.2 1.2,3.2 0,4.4l-3.9,3.9c-0.9,0.9 -2.2,1.4 -3.5,1.4z" fill="#54e6eb" data-v-ac41ac96=""></path></g></g></svg>
                        </a>
                    </div>
                </div>
            </div>
            <MediaSlider v-if="item.news?.media?.length" :medias="item.news.media" />
            <h3 style="margin: 10px;">{{ item.news.description }}</h3>
            <div class="address" v-if="item.news.address?.address">
                {{ item.news.address.address }}
            </div>
        </section>
        <button class="add" v-if="item.has_rule" @click="openModal">✏️</button>
        <h2>پیوست</h2>
        <section class="report-section">
            <div class="report-header">
                <div v-if="!item.has_rule" class="user-info">
                    <img v-if="item.user?.image" :src="item.user.image" class="avatar" />
                    <svg v-else class="avatar" xmlns="http://www.w3.org/2000/svg" fill="#000000" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><g data-v-ab5c279c=""><rect data-v-ab5c279c="" fill="none" height="24" width="24"></rect></g><g data-v-ab5c279c=""><path data-v-ab5c279c="" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm0 14c-2.03 0-4.43-.82-6.14-2.88C7.55 15.8 9.68 15 12 15s4.45.8 6.14 2.12C16.43 19.18 14.03 20 12 20z"></path></g></svg>
                    <div class="user">
                        <strong>{{ item.user.name }} {{ item.user.family }}</strong>
                        <a v-if="item.user?.phone" :href="`tel:${item.user.phone}`">
                            <svg height="25" width="25" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0,0,256,256" data-v-ac41ac96=""><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal;" data-v-ac41ac96=""><g transform="scale(4,4)" data-v-ac41ac96=""><path d="M1,32c0,-17.1 13.9,-31 31,-31c17.1,0 31,13.9 31,31c0,17.1 -13.9,31 -31,31c-17.1,0 -31,-13.9 -31,-31z" fill="#3ec81c" data-v-ac41ac96=""></path><path d="M43.1,51.4c-0.7,0 -1.3,-0.1 -1.9,-0.4c-3.6,-1.6 -10.6,-5.1 -16.8,-11.3c-6.2,-6.2 -9.8,-13.3 -11.4,-16.9c-0.8,-1.8 -0.4,-3.9 1.1,-5.4l3.8,-3.8c0.5,-0.5 1.1,-0.9 1.8,-1c1,-0.2 2,0.2 2.7,0.9l4.9,4.9c1.3,1.3 1.3,3.4 0,4.8l-3.9,3.9c-0.4,0.4 -0.5,1 -0.3,1.5c0.7,1.5 2.3,4.6 5,7.3c2.7,2.7 5.8,4.3 7.3,5c0.5,0.2 1.1,0.1 1.5,-0.3l4,-4c0.5,-0.5 1,-0.8 1.7,-0.9c1,-0.2 2,0.1 2.8,0.9l5.1,5.1c1.2,1.2 1.2,3.2 0,4.4l-3.9,3.9c-0.9,0.9 -2.2,1.4 -3.5,1.4z" fill="#54e6eb" data-v-ac41ac96=""></path></g></g></svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="report-content">
                <MediaSlider v-if="item.report?.media?.length" :medias="item.report.media" />
                <h3>{{ item.report?.description || 'بدون پیوست' }}</h3>
                <div class="times">
                    <small>ثبت: {{ moment(item.report?.created_at).format('jYYYY/jMM/jDD') }}</small>
                    <small>اجرا: {{ moment(item.report?.run_time).format('jYYYY/jMM/jDD') }}</small>
                </div>
            </div>
        </section>
        <div v-if="isModalOpen" class="modal-overlay">
            <div class="modal">
                <h3>پیوست گزارش</h3>
                <UploaderManyMedia 
                :url="'report_media/'"
                :toAction="'report'"
                v-model="item.report.media"
                @done="handleUploadResult" 
                />
                <form @submit.prevent="submitForm">
                    <textarea v-model="description" rows="4" placeholder="توضیحات..." />
                    <div class="modal-actions">
                        <button type="submit">ثبت</button>
                        <button @click.prevent="isModalOpen = false">بستن</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script setup>
    import { ref, onMounted, onUnmounted, defineProps, watch, computed } from 'vue'
    import { useCartableStore } from '@/stores/cartable'
    import MediaSlider from '@/components/tooles/media/MediaSlider.vue'
    import UploaderManyMedia from '@/components/tooles/upload/UploaderManyMedia.vue'
    import { sendApi } from '@/utils/api'
    import moment from 'moment-jalaali'
    const props = defineProps({ id: Number })
    const store = useCartableStore()
    const item = computed(() => store.getCartableById(props.id))
    const isModalOpen = ref(false)
    const description = ref('')
    const mediaIds = ref([])
    const openModal = () => {
        if (item.value) {
            description.value = item.value.report?.description || ''
            mediaIds.value = item.value.report?.media?.map(m => m.id) || []
            isModalOpen.value = true
        }
    }
    const handleUploadResult = (uploaded) => {
        mediaIds.value = uploaded.map(m => m.id)
    }
    const submitForm = async () => {
        const res = await sendApi({
            control: 'news',
            action: 'edit_report',
            data: {
                id: props.id,
                description: description.value,
                media_id: mediaIds.value,
            }
        })
        if (res.status === 'success') {
            await store.fetchCartables()
            isModalOpen.value = false
        } else {
            alert('خطا: ' + res.message)
        }
    }
    onMounted(async () => {
        if (!store.allItems.length) {
            await store.fetchCartables()
        }
        store.startPolling()
    })
    onUnmounted(() => store.stopPolling())
    watch(() => props.id, async () => {
        if (!store.allItems.length) {
            await store.fetchCartables()
        }
    })
</script>
<style scoped>
    .add{
        float: left;
        border: none;
        outline: none;
        padding: 10px;
        background: #9cf0a4;
        border-radius: 5px;
    }
    .address{
        margin: 0 10px 20px 10px;
        word-break: break-all;
    }
    .user,.times{
        display: flex;
        flex-wrap: nowrap;
        justify-content: space-between;
        align-items: center;
    }
    .times{
        flex-direction: row;
    }
    .user{
        width: 100%;
    }
    .cartable-details {
        padding: 1rem;
        direction: rtl;
    }
    .news-section, .report-section {
        margin-bottom: 2rem;
        border: 1px solid #eee;
        border-radius: 10px;
        overflow: hidden;
    }
    .news-header,.report-header {
        background: rgb(255, 242, 218);
        padding: 10px;
        margin-bottom: 0.7rem;
    }
    .report-content {
        padding: 0 10px 15px;
    }
    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }
    .modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .modal {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        width: 90%;
        max-width: 500px;
        max-height: 400px;
        overflow-y: auto;
    }
    .modal-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 1rem;
    }
    .modal-actions button {
        width: 100%;
        font-size: 16px;
        padding: 7px;
        margin: 0 5px;
        border-radius: 5px;
        outline: none;
        border: none;
        color: white;
        background-color: rgb(117, 4, 4);
    }
    .modal-actions button:first-child{
        background-color: rgb(4, 88, 4);
    }
    textarea {
        width: 100%;
        margin-top: 1rem;
        outline: none;
        padding: 10px;
        border-radius: 10px;
        box-sizing: border-box;
    }
</style>