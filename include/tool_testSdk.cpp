#include "WeWorkFinanceSdk_C.h"
#include <dlfcn.h>
#include <stdint.h>
#include <stdio.h>
#include <stdlib.h>
#include <string>
using std::string;

typedef WeWorkFinanceSdk_t* newsdk_t();
typedef int Init_t(WeWorkFinanceSdk_t*, const char*, const char*);
typedef void DestroySdk_t(WeWorkFinanceSdk_t*);

typedef int GetChatData_t(WeWorkFinanceSdk_t*, unsigned long long, unsigned int, const char*, const char*, int, Slice_t*);
typedef Slice_t* NewSlice_t();
typedef void FreeSlice_t(Slice_t*);

typedef int GetMediaData_t(WeWorkFinanceSdk_t*, const char*, const char*, const char*, const char*, int, MediaData_t*);
typedef int DecryptData_t(const char*, const char*, Slice_t*);
typedef MediaData_t* NewMediaData_t();
typedef void FreeMediaData_t(MediaData_t*);

int main(int argc, char* argv[])
{
    int ret = 0;
	//seq 锟斤拷示锟斤拷锟斤拷业锟芥档锟斤拷息锟斤拷牛锟斤拷锟斤拷锟脚碉拷锟斤拷锟斤拷锟斤拷锟斤拷锟斤拷取锟斤拷沤锟斤拷锟斤拷锟斤拷锟轿拷洗锟斤拷锟饺★拷锟斤拷亟锟斤拷锟斤拷锟斤拷锟斤拷锟脚★拷锟阶达拷锟斤拷取时seq锟斤拷0锟斤拷sdk锟结返锟斤拷锟斤拷效锟斤拷锟斤拷锟斤拷锟斤拷锟斤拷锟较拷锟�
	//limit 锟斤拷示锟斤拷锟斤拷锟斤拷取锟斤拷锟斤拷锟斤拷锟较拷锟斤拷锟斤拷锟饺≈碉拷锟轿�1~1000
	//proxy锟斤拷passwd为锟斤拷锟斤拷锟斤拷锟斤拷锟斤拷锟斤拷锟斤拷锟斤拷锟絪dk锟侥伙拷锟斤拷锟斤拷锟斤拷直锟接凤拷锟斤拷锟斤拷锟斤拷锟斤拷锟斤拷要锟斤拷锟矫达拷锟斤拷锟斤拷锟斤拷锟斤拷sdk锟斤拷锟绞碉拷锟斤拷锟斤拷锟斤拷"https://qyapi.weixin.qq.com"锟斤拷
	//锟斤拷锟斤拷锟斤拷通锟斤拷curl锟斤拷锟斤拷"https://qyapi.weixin.qq.com"锟斤拷锟斤拷证锟斤拷锟斤拷锟斤拷锟斤拷锟斤拷确锟斤拷锟劫达拷锟斤拷sdk锟斤拷
	//timeout 为锟斤拷取锟结话锟芥档锟侥筹拷时时锟戒，锟斤拷位为锟诫，锟斤拷锟介超时时锟斤拷锟斤拷锟斤拷为5s锟斤拷
	//sdkfileid 媒锟斤拷锟侥硷拷id锟斤拷锟接斤拷锟杰猴拷幕峄帮拷娴碉拷械玫锟�
	//savefile 媒锟斤拷锟侥硷拷锟斤拷锟斤拷路锟斤拷
	//encrypt_key 锟斤拷取锟结话锟芥档锟斤拷锟截碉拷encrypt_random_key锟斤拷使锟斤拷锟斤拷锟斤拷锟斤拷锟斤拷业微锟脚癸拷锟斤拷台锟斤拷rsa锟斤拷钥锟斤拷应锟斤拷私钥锟斤拷锟杰猴拷玫锟絜ncrypt_key锟斤拷
	//encrypt_chat_msg 锟斤拷取锟结话锟芥档锟斤拷锟截碉拷encrypt_chat_msg
    if (argc < 2) {
        printf("./sdktools 1(chatmsg) 2(mediadata) 3(decryptdata)\n");
        printf("./sdktools 1 seq limit proxy passwd timeout\n");
        printf("./sdktools 2 fileid proxy passwd timeout savefile\n");
        printf("./sdktools 3 encrypt_key encrypt_chat_msg\n");
        return -1;
    }

    void* so_handle = dlopen("./libWeWorkFinanceSdk_C.so", RTLD_LAZY);
    if (!so_handle) {
        printf("load sdk so fail:%s\n", dlerror());
        return -1;
    }
    newsdk_t* newsdk_fn = (newsdk_t*)dlsym(so_handle, "NewSdk");
    WeWorkFinanceSdk_t* sdk = newsdk_fn();

	//使锟斤拷sdk前锟斤拷要锟斤拷始锟斤拷锟斤拷锟斤拷始锟斤拷锟缴癸拷锟斤拷锟絪dk锟斤拷锟斤拷一直使锟矫★拷
	//锟斤拷锟借并锟斤拷锟斤拷锟斤拷sdk锟斤拷锟斤拷锟斤拷每锟斤拷锟竭程筹拷锟斤拷一锟斤拷sdk实锟斤拷锟斤拷
	//锟斤拷始锟斤拷时锟斤拷锟斤拷锟斤拷锟皆硷拷锟斤拷业锟斤拷corpid锟斤拷secrectkey锟斤拷
    Init_t* init_fn = (Init_t*)dlsym(so_handle, "Init");
    DestroySdk_t* destroysdk_fn = (DestroySdk_t*)dlsym(so_handle, "DestroySdk");
    ret = init_fn(sdk, "wwdf65802ca25ec195", "-Ta6WMWxBhfGolWnnlO15nQckj3DRKAowUOdX2fwvzE");
    if (ret != 0) {
        //sdk锟斤拷要锟斤拷锟斤拷锟酵凤拷
        destroysdk_fn(sdk);
        printf("init sdk err ret:%d\n", ret);
        return -1;
    }

    int type = strtoul(argv[1], NULL, 10);
    if (type == 1) {
        //锟斤拷取锟结话锟芥档
        uint64_t iSeq = strtoul(argv[2], NULL, 10);
        uint64_t iLimit = strtoul(argv[3], NULL, 10);
        uint64_t timeout = strtoul(argv[6], NULL, 10);

        NewSlice_t* newslice_fn = (NewSlice_t*)dlsym(so_handle, "NewSlice");
        FreeSlice_t* freeslice_fn = (FreeSlice_t*)dlsym(so_handle, "FreeSlice");

		//每锟斤拷使锟斤拷GetChatData锟斤拷取锟芥档前锟斤拷要锟斤拷锟斤拷NewSlice锟斤拷取一锟斤拷chatDatas锟斤拷锟斤拷使锟斤拷锟斤拷chatDatas锟斤拷锟斤拷锟捷后，伙拷锟斤拷要锟斤拷锟斤拷FreeSlice锟酵放★拷
        Slice_t* chatDatas = newslice_fn();
        GetChatData_t* getchatdata_fn = (GetChatData_t*)dlsym(so_handle, "GetChatData");
        ret = getchatdata_fn(sdk, iSeq, iLimit, argv[4], argv[5], timeout, chatDatas);
        if (ret != 0) {
            freeslice_fn(chatDatas);
            printf("GetChatData err ret:%d\n", ret);
            return -1;
        }
        printf("GetChatData len:%d data:%s\n", chatDatas->len, chatDatas->buf);
        freeslice_fn(chatDatas);
    }
    else if (type == 2) {
		//锟斤拷取媒锟斤拷锟侥硷拷
        std::string index;
        uint64_t timeout = strtoul(argv[5], NULL, 10);
        int isfinish = 0;

        GetMediaData_t* getmediadata_fn = (GetMediaData_t*)dlsym(so_handle, "GetMediaData");
        NewMediaData_t* newmediadata_fn = (NewMediaData_t*)dlsym(so_handle, "NewMediaData");
        FreeMediaData_t* freemediadata_fn = (FreeMediaData_t*)dlsym(so_handle, "FreeMediaData");

		//媒锟斤拷锟侥硷拷每锟斤拷锟斤拷取锟斤拷锟斤拷锟絪ize为512k锟斤拷锟斤拷顺锟斤拷锟�512k锟斤拷锟侥硷拷锟斤拷要锟斤拷片锟斤拷取锟斤拷锟斤拷锟斤拷锟侥硷拷未锟斤拷取锟斤拷锟斤拷锟斤拷mediaData锟叫碉拷is_finish锟结返锟斤拷0锟斤拷同时mediaData锟叫碉拷outindexbuf锟结返锟斤拷锟铰达拷锟斤拷取锟斤拷要锟斤拷锟斤拷GetMediaData锟斤拷indexbuf锟斤拷
		//indexbuf一锟斤拷锟绞斤拷锟斤拷也锟斤拷锟绞撅拷锟斤拷锟絉ange:bytes=524288-1048575锟斤拷锟斤拷锟斤拷示锟斤拷锟斤拷锟饺★拷锟斤拷谴锟�524288锟斤拷1048575锟侥凤拷片锟斤拷锟斤拷锟斤拷锟侥硷拷锟阶达拷锟斤拷取锟斤拷写锟斤拷indexbuf为锟斤拷锟街凤拷锟斤拷锟斤拷锟斤拷取锟斤拷锟斤拷锟斤拷片时直锟斤拷锟斤拷锟斤拷锟较次凤拷锟截碉拷indexbuf锟斤拷锟缴★拷
        while (isfinish == 0) {
            //每锟斤拷使锟斤拷GetMediaData锟斤拷取锟芥档前锟斤拷要锟斤拷锟斤拷NewMediaData锟斤拷取一锟斤拷mediaData锟斤拷锟斤拷使锟斤拷锟斤拷mediaData锟斤拷锟斤拷锟捷后，伙拷锟斤拷要锟斤拷锟斤拷FreeMediaData锟酵放★拷
            printf("index:%s\n", index.c_str());
            MediaData_t* mediaData = newmediadata_fn();
            ret = getmediadata_fn(sdk, index.c_str(), argv[2], argv[3], argv[4], timeout, mediaData);
            if (ret != 0) {
                //锟斤拷锟斤拷锟斤拷片锟斤拷取失锟杰斤拷锟斤拷锟斤拷锟斤拷锟斤拷取锟矫凤拷片锟斤拷锟斤拷锟斤拷锟酵凤拷锟绞硷拷锟饺★拷锟�
                freemediadata_fn(mediaData);
                printf("GetMediaData err ret:%d\n", ret);
                return -1;
            }
            printf("content size:%d isfin:%d outindex:%s\n", mediaData->data_len, mediaData->is_finish, mediaData->outindexbuf);

			//锟斤拷锟斤拷512k锟斤拷锟侥硷拷锟斤拷锟狡拷锟饺★拷锟斤拷舜锟斤拷锟揭癸拷锟阶凤拷锟叫达拷锟斤拷锟斤拷锟斤拷锟斤拷姆锟狡拷锟斤拷锟街帮拷锟斤拷锟斤拷荨锟�
            char file[200];
            snprintf(file, sizeof(file), "%s", argv[6]);
            FILE* fp = fopen(file, "ab+");
            printf("filename:%s \n", file);
            if (NULL == fp) {
                freemediadata_fn(mediaData);
                printf("open file err\n");
                return -1;
            }

            fwrite(mediaData->data, mediaData->data_len, 1, fp);
            fclose(fp);

            //锟斤拷取锟铰达拷锟斤拷取锟斤拷要使锟矫碉拷indexbuf
            index.assign(string(mediaData->outindexbuf));
            isfinish = mediaData->is_finish;
            freemediadata_fn(mediaData);
        }
    }
    else if (type == 3) {
		//锟斤拷锟杰会话锟芥档锟斤拷锟斤拷
		//sdk锟斤拷锟斤拷要锟斤拷锟矫伙拷锟斤拷锟斤拷rsa私钥锟斤拷锟斤拷证锟矫伙拷锟结话锟芥档锟斤拷锟斤拷只锟斤拷锟皆硷拷锟杰癸拷锟斤拷锟杰★拷
		//锟剿达拷锟斤拷要锟矫伙拷锟斤拷锟斤拷rsa私钥锟斤拷锟斤拷encrypt_random_key锟斤拷锟斤拷为encrypt_key锟斤拷锟斤拷锟斤拷锟斤拷sdk锟斤拷锟斤拷锟斤拷encrypt_chat_msg锟斤拷取锟结话锟芥档锟斤拷锟侥★拷
		//每锟斤拷使锟斤拷DecryptData锟斤拷锟杰会话锟芥档前锟斤拷要锟斤拷锟斤拷NewSlice锟斤拷取一锟斤拷Msgs锟斤拷锟斤拷使锟斤拷锟斤拷Msgs锟斤拷锟斤拷锟捷后，伙拷锟斤拷要锟斤拷锟斤拷FreeSlice锟酵放★拷
        NewSlice_t* newslice_fn = (NewSlice_t*)dlsym(so_handle, "NewSlice");
        FreeSlice_t* freeslice_fn = (FreeSlice_t*)dlsym(so_handle, "FreeSlice");

        Slice_t* Msgs = newslice_fn();
        // decryptdata api
        DecryptData_t* decryptdata_fn = (DecryptData_t*)dlsym(so_handle, "DecryptData");
        ret = decryptdata_fn(argv[2], argv[3], Msgs);
        printf("chatdata :%s ret :%d\n", Msgs->buf, ret);

        freeslice_fn(Msgs);
    }

    return ret;
}
