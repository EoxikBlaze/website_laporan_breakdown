import * as React from "react";
import { format } from "date-fns";
import { id } from "date-fns/locale";
import { Calendar } from "@/Components/ui/calendar";
import { TimePicker } from "@/Components/ui/time-picker";
import { Popover, PopoverContent, PopoverTrigger } from "@/Components/ui/popover";
import { Button } from "@/Components/ui/button";
import { CalendarIcon, Clock } from "lucide-react";
import { cn } from "@/lib/utils";

interface IosDateTimePickerProps {
    name: string;
    initialValue?: string;
    label?: string;
}

export function IosDateTimePicker({ name, initialValue, label }: IosDateTimePickerProps) {
    const [date, setDate] = React.useState<Date | undefined>(
        initialValue ? new Date(initialValue) : new Date()
    );
    const [isOpen, setIsOpen] = React.useState(false);

    const formattedValue = date ? format(date, "yyyy-MM-dd HH:mm") : "";
    const displayValue = date ? format(date, "dd/MM/yyyy, HH.mm") : "Pilih waktu...";

    // Handle time change from our new TimePicker
    const handleTimeChange = (newDate: Date) => {
        setDate(newDate);
    };

    return (
        <div className="ios-datetime-picker-wrapper">
            <input type="hidden" name={name} value={formattedValue} />
            
            <Popover open={isOpen} onOpenChange={setIsOpen}>
                <PopoverTrigger asChild>
                    <Button
                        variant={"outline"}
                        className={cn(
                            "w-full justify-start text-left font-normal h-10 border-[#d1d5db] rounded-lg bg-white hover:border-primary/50 transition-all focus:ring-2 focus:ring-primary/20",
                            !date && "text-muted-foreground"
                        )}
                    >
                        <span className="flex-1 font-semibold text-sm">{displayValue}</span>
                        <CalendarIcon className="ml-2 h-4 w-4 opacity-50" />
                    </Button>
                </PopoverTrigger>
                <PopoverContent 
                    className="w-[90vw] md:w-[320px] p-0 border-none shadow-xl rounded-[20px] overflow-hidden bg-white max-h-[85vh] overflow-y-auto" 
                    align="start"
                >
                    <div className="flex flex-col divide-y divide-neutral-100">
                        {/* Header Section */}
                        <div className="px-5 py-3 bg-blue-50/50">
                            <div className="flex flex-col text-blue-700">
                                <span className="text-[9px] uppercase tracking-wider font-extrabold opacity-60">Waktu Terpilih</span>
                                <span className="text-sm font-bold">
                                    {date ? format(date, "EEEE, dd MMM yyyy", { locale: id }) : "Pilih Tanggal"}
                                </span>
                            </div>
                        </div>

                        {/* Calendar Part */}
                        <div className="p-2 flex justify-center scale-[0.95] origin-top">
                            <Calendar
                                mode="single"
                                selected={date}
                                onSelect={(d) => d && setDate(d)}
                                locale={id}
                                initialFocus
                                className="rounded-md border-none"
                            />
                        </div>

                        {/* Middle Divider with Icon */}
                        <div className="relative py-1 flex items-center justify-center">
                            <div className="absolute inset-0 flex items-center px-6">
                                <div className="w-full border-t border-dashed border-neutral-100"></div>
                            </div>
                            <div className="relative bg-white px-2 flex items-center gap-1.5 text-neutral-400">
                                <Clock size={12} />
                                <span className="text-[9px] font-bold uppercase tracking-wider leading-none">Waktu</span>
                            </div>
                        </div>

                        {/* Clock Part */}
                        <div className="p-4 pt-2">
                            <TimePicker 
                                value={date} 
                                onChange={handleTimeChange} 
                            />
                            
                            <div className="mt-4">
                                <Button 
                                    className="w-full bg-blue-600 hover:bg-blue-700 text-white rounded-xl h-10 shadow-lg shadow-blue-600/20 font-bold transition-all active:scale-[0.98] text-xs"
                                    onClick={() => setIsOpen(false)}
                                >
                                    Selesai
                                </Button>
                            </div>
                        </div>
                    </div>
                </PopoverContent>
            </Popover>
        </div>
    );
}
