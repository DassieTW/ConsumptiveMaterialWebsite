import { ref, Ref, reactive, computed } from "vue";

export const searchTerm = ref(""); // Search text
export const data = reactive([]);
export const table = reactive({
    isLoading: false,
    columns: [
        {
            label: Lang.get("monthlyPRpageLang.isn"),
            field: "料號",
            width: "14ch",
            sortable: true,
        },
        {
            label: Lang.get("monthlyPRpageLang.pName"),
            field: "品名",
            width: "14ch",
            sortable: true,
            display: function (row, i) {
                return (
                    '<div class="scrollableWithoutScrollbar text-nowrap"' +
                    ' style="overflow-x: auto; width: 100%;">' +
                    row.品名 +
                    "</div>"
                );
            },
        },
        {
            label: Lang.get("monthlyPRpageLang.buyamount1"),
            field: "請購數量",
            width: "12ch",
            sortable: true,
        },
        {
            label: Lang.get("outboundpageLang.realpickamount"),
            field: "實際領用數量",
            width: "10ch",
            sortable: true,
        },
        {
            label: Lang.get("callpageLang.req_vs_real"),
            field: "需求與領用差異量",
            width: "13ch",
            sortable: true,
        },
        {
            label: Lang.get("callpageLang.req_vs_real_percent"),
            field: "需求與領用差異",
            width: "13ch",
            sortable: true,
        },
    ],
    rows: computed(() => {
        return data.filter(
            (x) =>
                x.料號.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
                x.品名.includes(searchTerm.value)
        );
    }),
    totalRecordCount: computed(() => {
        return table.rows.length;
    }),
    sortable: {
        order: "id",
        sort: "asc",
    },
    messages: {
        pagingInfo:
            Lang.get("basicInfoLang.now_showing") +
            " {0} ~ {1} " +
            Lang.get("basicInfoLang.record") +
            ", " +
            Lang.get("basicInfoLang.total") +
            " {2} " +
            Lang.get("basicInfoLang.record"),
        pageSizeChangeLabel: Lang.get("basicInfoLang.records_per_page"),
        gotoPageLabel: Lang.get("basicInfoLang.go_to_page"),
        noDateAvailable: Lang.get("basicInfoLang.search_with_no_data_returned"),
    },
    pageOptions: [
        {
            value: 10,
            text: 10,
        },
        {
            value: 20,
            text: 20,
        },
        {
            value: 40,
            text: 40,
        },
        {
            value: 60,
            text: 60,
        },
    ],
});
